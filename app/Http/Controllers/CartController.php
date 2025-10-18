<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class CartController extends Controller
{
    /**
     * 🛒 Hiển thị giỏ hàng
     */
    public function index()
    {
        $items = Cart::getContent();
        $total = Cart::getTotal();

        return view('pages.cart', compact('items', 'total'));
    }

    /**
     * ➕ Thêm sản phẩm vào giỏ hàng (AJAX)
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'name'       => 'required|string',
            'price'      => 'required|numeric|min:0',
            'quantity'   => 'nullable|integer|min:1',
        ]);

        try {
            Cart::add([
                'id'       => $request->input('product_id'),
                'name'     => $request->input('name'),
                'price'    => (int) $request->input('price'),
                'quantity' => (int) $request->input('quantity', 1),
                'attributes' => [
                    'color' => $request->input('color'),
                    'size'  => $request->input('size'),
                    'image' => $request->input('image'),
                ],
            ]);

            return response()->json([
                'status'      => 'success',
                'message'     => '🛍️ Đã thêm sản phẩm vào giỏ hàng!',
                'totalItems'  => Cart::getTotalQuantity(),
                'totalPrice'  => Cart::getTotal(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Lỗi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * ✏️ Cập nhật số lượng sản phẩm
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        Cart::update($id, [
            'quantity' => [
                'relative' => false,
                'value'    => (int) $request->input('quantity'),
            ],
        ]);

        return redirect()->back()->with('success', '✅ Cập nhật giỏ hàng thành công.');
    }

    /**
     * ❌ Xóa 1 sản phẩm khỏi giỏ
     */
    public function remove($id)
    {
        Cart::remove($id);
        return redirect()->back()->with('success', '🗑️ Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    /**
     * 🧹 Xóa toàn bộ giỏ hàng
     */
    public function clear()
    {
        Cart::clear();
        return redirect()->back()->with('success', '🧺 Giỏ hàng đã được xóa toàn bộ.');
    }
}
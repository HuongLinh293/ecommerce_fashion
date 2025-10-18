<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    // Show the current user's wishlist
    public function index(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login.form');
        }

        $items = Wishlist::with('product')->where('user_id', $user->id)->latest()->paginate(12);

        return view('pages.wishlist.index', compact('items'));
    }

    // Toggle wishlist for a product (AJAX)
    public function toggle(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $productId = (int) $request->input('product_id');
        $product = Product::find($productId);
        if (! $product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $existing = Wishlist::where('user_id', $user->id)->where('product_id', $productId)->first();
        if ($existing) {
            $existing->delete();
            return response()->json(['action' => 'removed']);
        }

        Wishlist::create(['user_id' => $user->id, 'product_id' => $productId]);
        return response()->json(['action' => 'added']);
    }

    // Remove specific wishlist item
    public function remove(Request $request, $id)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login.form');
        }

        $item = Wishlist::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $item->delete();
        return back()->with('success', 'Đã xoá khỏi wishlist');
    }
        public function destroy($id)
    {
        $wishlistItem = auth()->user()->wishlists()->findOrFail($id);
        $wishlistItem->delete();

        return redirect()->route('wishlist.index')->with('success', 'Đã xóa sản phẩm khỏi wishlist.');
    }
}
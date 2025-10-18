<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    // 📋 Danh sách sản phẩm
    public function index(Request $request)
    {
        $query = Product::query();

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Phân trang
        $products = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Normalize a value to an array. Accepts array, JSON string, comma-separated string, or null.
     */
    private function parseToArray($value): array
    {
        if (is_array($value)) return $value;
        if (is_string($value)) {
            $trim = trim($value);
            if ($trim === '') return [];
            if (str_starts_with($trim, '[')) {
                $decoded = json_decode($trim, true);
                return is_array($decoded) ? $decoded : [];
            }
            // comma separated
            return array_map('trim', explode(',', $value));
        }
        return [];
    }

    // ➕ Trang tạo sản phẩm
    public function create()
    {
        return view('admin.products.create');
    }

    // 💾 Lưu sản phẩm mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|string|max:255',
            'category'       => 'required|string|max:50',
            'price'          => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description'    => 'required|string',
            'material'       => 'nullable|string|max:255',
            'colors'         => 'required',
            'sizes'          => 'required',
            'image'          => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Xử lý upload ảnh chính
            if ($request->hasFile('image')) {
                $img = $request->file('image');
                $imgName = uniqid('product_') . '.' . $img->getClientOriginalExtension();
                $img->move(public_path('assets/products'), $imgName);
                $validated['image'] = '/assets/products/' . $imgName;
            }

        // Xử lý ảnh phụ (gallery) - lưu dưới dạng mảng để Eloquent cast về JSON
            $galleryPaths = [];
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $gName = uniqid('gallery_') . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('assets/products'), $gName);
                    $galleryPaths[] = '/assets/products/' . $gName;
                }
            }
            $validated['gallery'] = $galleryPaths;

    // Normalize colors/sizes to arrays
    $validated['colors'] = $this->parseToArray($validated['colors'] ?? '');
    $validated['sizes'] = $this->parseToArray($validated['sizes'] ?? '');

    // Trạng thái hiển thị
    $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        // Tạo slug từ tên
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Đã tạo sản phẩm mới!');
    }

    // ✏️ Trang chỉnh sửa
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    // 🔁 Cập nhật sản phẩm
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|string|max:255',
            'category'       => 'required|string|max:50',
            'price'          => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description'    => 'required|string',
            'material'       => 'nullable|string|max:255',
            'colors'         => 'required',
            'sizes'          => 'required',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);


        // Nếu có ảnh chính mới → xóa ảnh cũ và lưu vào public/assets/products
        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path($product->image))) {
                @unlink(public_path($product->image));
            }
            $imgFile = $request->file('image');
            $imgName = uniqid('product_') . '.' . $imgFile->getClientOriginalExtension();
            $imgFile->move(public_path('assets/products'), $imgName);
            $validated['image'] = '/assets/products/' . $imgName;
        }

        // Nếu có ảnh phụ mới → ghi đè vào public/assets/products
        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            foreach ($request->file('gallery') as $file) {
                $galleryName = uniqid('gallery_') . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets/products'), $galleryName);
                $galleryPaths[] = '/assets/products/' . $galleryName;
            }
            $validated['gallery'] = $galleryPaths;
        }

        // Normalize colors/sizes to arrays
        $validated['colors'] = $this->parseToArray($validated['colors'] ?? '');
        $validated['sizes'] = $this->parseToArray($validated['sizes'] ?? '');

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật thành công!');
    }

    // ❌ Xóa sản phẩm
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Xóa ảnh chính
        if ($product->image) {
            // If it was saved to /assets/products -> remove from public path
            if (str_starts_with($product->image, '/assets/')) {
                $assetPath = public_path(ltrim($product->image, '/'));
                if (file_exists($assetPath)) {
                    @unlink($assetPath);
                }
            }

            // If it was saved using Storage::disk('public') -> path like /storage/...
            if (str_starts_with($product->image, '/storage/')) {
                $storagePath = str_replace('/storage/', 'public/', $product->image);
                if (Storage::exists($storagePath)) {
                    Storage::delete($storagePath);
                }
            }
        }

        // Xóa gallery (hỗ trợ cả /assets/ và /storage/ URLs)
        $images = $this->parseToArray($product->gallery);
        foreach ($images as $img) {
            if (str_starts_with($img, '/assets/')) {
                $path = public_path(ltrim($img, '/'));
                if (file_exists($path)) {
                    @unlink($path);
                }
                continue;
            }

            if (str_starts_with($img, '/storage/')) {
                $storagePath = str_replace('/storage/', 'public/', $img);
                if (Storage::exists($storagePath)) {
                    Storage::delete($storagePath);
                }
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm!');
    }
}
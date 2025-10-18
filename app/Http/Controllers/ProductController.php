<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Trang phụ kiện - lấy từ file products.json
     */
    public function accessories(Request $request)
    {
        // Load accessories from database so edits in admin/DB are reflected immediately
        $query = Product::query()->where('category', 'accessories');

        $subcategory = $request->query('subcategory');
        if ($subcategory) {
            // support both lowercase and ucfirst stored values
            $query->where('type', $subcategory)->orWhere('type', ucfirst($subcategory));
        }

        // Convert to array so the accessories view (which expects array shape) continues to work
        $accessories = $query->orderBy('id', 'desc')->get()->map(function ($p) {
            return $p->toArray();
        })->values()->all();

        $wishlistedIds = [];
        if (Auth::check()) {
            $wishlistedIds = \App\Models\Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();
        }
        return view('pages.products.accessories', compact('accessories', 'subcategory', 'wishlistedIds'));
    }
    // 🟢 Trang tất cả sản phẩm
    public function all(Request $request)
    {
        return $this->index($request, 'all');
    }

    // 🟡 Trang danh sách sản phẩm (theo category/type)
    public function index(Request $request, $category = null, $type = null)
    {
        $query = Product::query();

        // Lọc theo category
        if (!empty($category) && $category !== 'all') {
            $query->where('category', $category);
        }

        // Lọc theo type (ví dụ: T-Shirt, Pants,...)
        if (!empty($type) && $type !== 'view-all') {
            $query->where('type', $type);
        }

        // Checkbox filters named types[] (sidebar checkboxes) — apply as whereIn
        if ($request->filled('types')) {
            $typesFilter = $request->types;
            if (!is_array($typesFilter)) {
                // support comma-separated string
                $typesFilter = explode(',', $typesFilter);
            }
            // normalize values
            $typesFilter = array_filter(array_map(function ($v) {
                return trim(mb_strtolower((string) $v));
            }, $typesFilter));
            if (!empty($typesFilter)) {
                // use case-insensitive comparison
                $query->whereIn(DB::raw('LOWER(`type`)'), $typesFilter);
            }
        }

        // Bộ lọc giá
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (int)$request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (int)$request->max_price);
        }

        // Bộ lọc màu sắc
        if ($request->filled('colors')) {
            $colors = is_array($request->colors) ? $request->colors : explode(',', $request->colors);
            $query->where(function ($q) use ($colors) {
                foreach ($colors as $color) {
                    $q->orWhereJsonContains('colors', $color);
                }
            });
        }

        // Bộ lọc size
        if ($request->filled('sizes')) {
            $sizes = is_array($request->sizes) ? $request->sizes : explode(',', $request->sizes);
            $query->where(function ($q) use ($sizes) {
                foreach ($sizes as $size) {
                    $q->orWhereJsonContains('sizes', $size);
                }
            });
        }

        // Tìm kiếm theo tên sản phẩm
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sắp xếp
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();

        // Debug: if types filter used, log request and matched product types
        if ($request->filled('types')) {
            try {
                Log::info('Product filter debug - request', $request->all());
                Log::info('Product filter debug - matched types', $products->pluck('type')->unique()->values()->toArray());
            } catch (\Throwable $e) {
                // don't break page if logging fails
            }
        }

        // Build sidebar filter lists from a category-scoped base (don't reuse already-filtered query)
        $sidebarBase = Product::query();
        if (!empty($category) && $category !== 'all') {
            $sidebarBase->where('category', $category);
        }

        $types = (clone $sidebarBase)->distinct()->pluck('type')->filter()->values();

        $colors = (clone $sidebarBase)->pluck('colors')
            ->map(fn($item) => is_string($item) ? json_decode($item, true) : $item)
            ->filter()->flatten()->unique()->values();

        $sizes = (clone $sidebarBase)->pluck('sizes')
            ->map(fn($item) => is_string($item) ? json_decode($item, true) : $item)
            ->filter()->flatten()->unique()->values();

        // Load current user's wishlist ids (if authenticated) so views can mark wished items
        $wishlistedIds = [];
        if (Auth::check()) {
            $wishlistedIds = \App\Models\Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();
        }

        return view('pages.products.index', compact(
            'products', 'category', 'type', 'types', 'colors', 'sizes', 'wishlistedIds'
        ));
    }

    

    // 🟢 Trang chi tiết sản phẩm
    public function show($id)
    {
        $product = Product::findOrFail($id);

        // 🧩 Lấy sản phẩm liên quan cùng category
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        // 📏 Bảng size guide text (hiển thị mô tả nhanh nếu cần)
        $sizeGuides = [
            'Pants' => "29: 72cm waist\n30: 76cm waist\n31: 80cm waist",
            'Shirt' => "S: 48cm chest\nM: 52cm chest\nL: 56cm chest",
        ];
        $sizeGuide = $sizeGuides[$product->type] ?? null;

        // 🖼️ Ảnh size guide động (tự chọn theo loại sản phẩm)
        $sizeGuideImage = null;
        if (strtolower($product->type) === 'shoes') {
            $sizeGuideImage = asset('assets/products/sizegiay.png');
        } else {
            $sizeGuideImage = asset('assets/products/sizequanao.jpg');
        }

        // Load user's wishlist ids
        $wishlistedIds = [];
        if (Auth::check()) {
            $wishlistedIds = \App\Models\Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();
        }

        // Trả dữ liệu ra view
        return view('pages.products.show', compact(
            'product',
            'sizeGuide',
            'relatedProducts',
            'sizeGuideImage',
            'wishlistedIds'
        ));
    }
    
    /**
     * API endpoint for quick product search (JSON)
     */
    public function searchApi(Request $request)
    {
        $q = $request->query('q');
        if (!$q) return response()->json([]);

        $results = Product::where('name', 'like', '%' . $q . '%')
            ->orWhere('type', 'like', '%' . $q . '%')
            ->limit(10)
            ->get(['id', 'name', 'type', 'price', 'image']);

        // format price for display
        $results->transform(function ($p) {
            $p->price = number_format($p->price, 0, ',', '.') . '₫';
            return $p;
        });

        return response()->json($results);
    }
    // 🟣 Hiển thị sản phẩm theo category (VD: /products/category/women)
    public function category($category, $type = null)
    {
        if ($category === 'accessories') {
            // Use DB-backed products so admin edits appear immediately
            $query = Product::query()->where('category', 'accessories');
            $subcategory = $type;
            if ($subcategory) {
                $query->where('type', $subcategory)->orWhere('type', ucfirst($subcategory));
            }
            $accessories = $query->orderBy('id', 'desc')->get()->map(fn($p) => $p->toArray())->values()->all();
            return view('pages.products.accessories', compact('accessories', 'subcategory'));
        }

        // Các category khác vẫn dùng index.blade.php
        $query = Product::query()
            ->where('category', $category);

        if ($type && $type !== 'view-all') {
            $query->where('type', $type);
        }

        $products = $query->paginate(12)->withQueryString();

        // Build sidebar lists from category base
        $sidebarBase = Product::query()->where('category', $category);
        $types = (clone $sidebarBase)->distinct()->pluck('type')->filter()->values();
        $colors = (clone $sidebarBase)->pluck('colors')
            ->map(fn($item) => is_string($item) ? json_decode($item, true) : $item)
            ->filter()->flatten()->unique()->values();
        $sizes = (clone $sidebarBase)->pluck('sizes')
            ->map(fn($item) => is_string($item) ? json_decode($item, true) : $item)
            ->filter()->flatten()->unique()->values();

        $wishlistedIds = [];
        if (Auth::check()) {
            $wishlistedIds = \App\Models\Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();
        }

        return view('pages.products.index', compact('products', 'category', 'type', 'types', 'colors', 'sizes', 'wishlistedIds'));
    }

}
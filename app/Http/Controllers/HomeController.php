<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // 🔹 Lấy ngẫu nhiên 10 sản phẩm thuộc các danh mục chính
        $readyToWearProducts = Product::whereIn('category', ['men', 'women', 'accessories'])
            ->inRandomOrder()
            ->take(10)
            ->get();

        // 🔹 Sản phẩm dành cho nữ (8 sản phẩm)
        $womenProducts = Product::where('category', 'women')
            ->inRandomOrder()
            ->take(8)
            ->get();

        // 🔹 Sản phẩm dành cho nam (8 sản phẩm)
        $menProducts = Product::where('category', 'men')
            ->inRandomOrder()
            ->take(8)
            ->get();

        // 🔹 Trả về view trang chủ
        return view('pages.home', [
            'readyToWearProducts' => $readyToWearProducts,
            'womenProducts' => $womenProducts,
            'menProducts' => $menProducts,
        ]);
    }
}
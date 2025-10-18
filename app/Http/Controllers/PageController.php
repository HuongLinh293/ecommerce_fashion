<?php

namespace App\Http\Controllers;

use App\Models\Product;

class PageController extends Controller
{
    public function home()
    {
        // 🔸 Lấy danh sách sản phẩm Ready-to-Wear
        $readyToWearProducts = Product::whereIn('category', ['men', 'women', 'accessories'])
            ->inRandomOrder()
            ->limit(10)
            ->get();

        // 🔸 Lấy danh sách sản phẩm cho từng danh mục
        $womenProducts = Product::where('category', 'women')
            ->inRandomOrder()
            ->limit(8)
            ->get();

        $menProducts = Product::where('category', 'men')
            ->inRandomOrder()
            ->limit(8)
            ->get();

        // 🔸 Truyền tất cả biến qua view
        return view('pages.home', compact(
            'readyToWearProducts',
            'womenProducts',
            'menProducts'
        ));
    }

    public function explore()
    {
        return view('pages.explore');
    }

    public function contact()
    {
        return view('pages.help');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ExploreController extends Controller
{
    public function index()
    {
        // Lấy 8 sản phẩm mới nhất
        $newArrivals = Product::latest()->take(8)->get();

        return view('pages.explore', compact('newArrivals'));
    }
}
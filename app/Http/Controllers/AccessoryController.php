<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccessoryController extends Controller
{
    public function index($subcategory = null)
    {
        $accessories = [
            [
                'id' => 101,
                'name' => 'VIVILLAN City Bag - Black',
                'price' => 5000000,
                'image' => asset('images/accessories/bag1.png'),
                'description' => 'Túi da thành phố cổ điển với phần cứng đặc trưng',
                'category' => 'PHỤ KIỆN',
                'type' => 'TÚI',
            ],
            [
                'id' => 102,
                'name' => 'VIVILLAN City Bag - Grey',
                'price' => 5000000,
                'image' => asset('images/accessories/bag2.png'),
                'description' => 'Túi da thành phố màu xám với họa tiết cổ điển',
                'category' => 'PHỤ KIỆN',
                'type' => 'TÚI',
            ],
            [
                'id' => 104,
                'name' => 'Classic Leather Sneakers',
                'price' => 3500000,
                'image' => 'https://images.unsplash.com/photo-1758702701300-372126112cb4?w=1200',
                'description' => 'Giày sneaker da cao cấp với thiết kế vượt thời gian',
                'category' => 'PHỤ KIỆN',
                'type' => 'GIÀY',
            ],
            [
                'id' => 105,
                'name' => 'Premium Leather Boots',
                'price' => 4500000,
                'image' => 'https://images.unsplash.com/photo-1652474590303-b4d72bf9f61a?w=1200',
                'description' => 'Boots da thủ công với độ bền vượt trội',
                'category' => 'PHỤ KIỆN',
                'type' => 'GIÀY',
            ],
            [
                'id' => 106,
                'name' => 'Canvas Sneakers',
                'price' => 1800000,
                'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=1200',
                'description' => 'Giày canvas thoải mái cho hàng ngày',
                'category' => 'PHỤ KIỆN',
                'type' => 'GIÀY',
            ],
            [
                'id' => 107,
                'name' => 'Classic Stiletto Heels',
                'price' => 3200000,
                'image' => 'https://images.unsplash.com/photo-1670607231621-c00fd76d2387?w=1200',
                'description' => 'Giày cao gót thanh lịch, quyền lực và nữ tính',
                'category' => 'PHỤ KIỆN',
                'type' => 'GIÀY CAO GÓT',
            ],
            [
                'id' => 108,
                'name' => 'Block Heel Pumps',
                'price' => 2800000,
                'image' => 'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=1200',
                'description' => 'Giày cao gót đế vuông thoải mái cả ngày',
                'category' => 'PHỤ KIỆN',
                'type' => 'GIÀY CAO GÓT',
            ],
            [
                'id' => 109,
                'name' => 'Strappy Sandal Heels',
                'price' => 2560000,
                'image' => 'https://images.unsplash.com/photo-1638247025967-b4e38f787b76?w=1200',
                'description' => 'Giày sandal cao gót quyến rũ cho buổi tối',
                'category' => 'PHỤ KIỆN',
                'type' => 'GIÀY CAO GÓT',
            ],
            [
                'id' => 103,
                'name' => 'Designer Tote Bag',
                'price' => 6500000,
                'image' => 'https://images.unsplash.com/photo-1758171692659-024183c2c272?w=1200',
                'description' => 'Túi tote rộng rãi hoàn hảo cho công việc và du lịch',
                'category' => 'PHỤ KIỆN',
                'type' => 'TÚI',
            ],
        ];

        // Lọc theo subcategory
        if ($subcategory === 'bag') {
            $accessories = array_filter($accessories, fn($item) => $item['type'] === 'TÚI');
        } elseif ($subcategory === 'shoes') {
            $accessories = array_filter($accessories, fn($item) => in_array($item['type'], ['GIÀY', 'GIÀY CAO GÓT']));
        }

        return view('pages.products.accessories', [
            'accessories' => $accessories,
            'subcategory' => $subcategory,
        ]);
    }
}
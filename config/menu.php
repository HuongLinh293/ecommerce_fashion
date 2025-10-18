<?php

return [
    [
        'id' => 'men',
        'label' => 'NAM',
        'hasDropdown' => true,
        'url' => '/products/category/men', 
        'subItems' => [
            ['id' => 'view-all', 'label' => 'Tất cả', 'url' => '/products/category/men'],
            ['id' => 't-shirt', 'label' => 'Áo thun', 'url' => '/products/category/men/t-shirt'],
            ['id' => 'pants', 'label' => 'Quần', 'url' => '/products/category/men/pants'],
            ['id' => 'coats-jackets', 'label' => 'Áo khoác', 'url' => '/products/category/men/coats-jackets'],
        ],
    ],
    [
        'id' => 'women',
        'label' => 'NỮ',
        'hasDropdown' => true,
        'url' => '/products/category/women',
        'subItems' => [
            ['id' => 'view-all', 'label' => 'Tất cả', 'url' => '/products/category/women'],
            ['id' => 'dress', 'label' => 'Đầm', 'url' => '/products/category/women/dress'],
            ['id' => 'skirt', 'label' => 'Chân váy', 'url' => '/products/category/women/skirt'],
            ['id' => 'top', 'label' => 'Áo', 'url' => '/products/category/women/top'],
            ['id' => 'jacket', 'label' => 'Áo khoác', 'url' => '/products/category/women/jacket'],
        ],
    ],
    [
        'id' => 'accessories',
        'label' => 'PHỤ KIỆN',
        'hasDropdown' => true,
        'url' => '/products/category/accessories',
        'subItems' => [
            ['id' => 'view-all', 'label' => 'Tất cả', 'url' => '/products/category/accessories'],
            ['id' => 'bag', 'label' => 'Túi xách', 'url' => '/products/category/accessories/bag'],
            ['id' => 'shoes', 'label' => 'Giày', 'url' => '/products/category/accessories/shoes'],
            
            
            
        ],
    ],
    [
        'id' => 'explore',
        'label' => 'KHÁM PHÁ',
        'hasDropdown' => false,
        'url' => '/explore',
    ],
    [
        'id' => 'contact',
        'label' => 'TRỢ GIÚP',
        'hasDropdown' => false,
        'url' => '/contact',
    ],
];
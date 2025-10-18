<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class ProductsDumpSeeder extends Command
{
    protected $signature = 'products:dump-seeder';
    protected $description = 'Dump current products to database/seeders/products.json';

    public function handle()
    {
        $products = Product::orderBy('id')->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'price' => $p->price,
                'originalPrice' => $p->original_price,
                'image' => $p->image,
                'category' => $p->category,
                'discount' => $p->discount,
                'type' => $p->type,
                'colors' => $p->colors,
                'sizes' => $p->sizes,
                'description' => $p->description,
                'material' => $p->material,
                'gallery' => $p->gallery,
            ];
        })->toArray();

        $data = ['products' => $products];

        $path = base_path('database/seeders/products.json');
        file_put_contents($path, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        $this->info('Dumped ' . count($products) . ' products to ' . $path);
        return 0;
    }
}

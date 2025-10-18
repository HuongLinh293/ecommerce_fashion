<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ✅ THÊM DÒNG NÀY
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('seeders/products.json');

        if (!file_exists($jsonPath)) {
            $this->command->error('❌ File products.json không tồn tại.');
            return;
        }

        $json = file_get_contents($jsonPath);
        $data = json_decode($json, true);

        if (!isset($data['products']) || !is_array($data['products'])) {
            $this->command->error('❌ products.json không hợp lệ.');
            return;
        }

        foreach ($data['products'] as $product) {
            DB::table('products')->updateOrInsert(
                ['id' => $product['id']],
                [
                    'name'           => $product['name'],
                    'price'          => $product['price'] ?? ($product['originalPrice'] ?? 0),
                    'original_price' => $product['originalPrice'] ?? $product['price'] ?? 0,
                    'image'          => $product['image'] ?? null,
                    'category'       => $product['category'] ?? null,
                    'discount'       => $product['discount'] ?? 0,
                    'type'           => $product['type'] ?? null,
                    'colors'         => json_encode($product['colors'] ?? []),
                    'sizes'          => json_encode($product['sizes'] ?? []),
                    'description'    => $product['description'] ?? null,
                    'material'       => $product['material'] ?? null,
                    'gallery'        => json_encode($product['gallery'] ?? []),
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]
            );
        }

        $this->command->info('✅ Đã seed dữ liệu sản phẩm thành công!');
    }
}
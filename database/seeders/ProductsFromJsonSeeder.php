<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductsFromJsonSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/products.json');
        if (!File::exists($path)) {
            $this->command->error('products.json not found at ' . $path);
            return;
        }

        $json = File::get($path);
        $data = json_decode($json, true);
        if (!isset($data['products']) || !is_array($data['products'])) {
            $this->command->error('Invalid products.json format');
            return;
        }

        foreach ($data['products'] as $p) {
            // Normalize fields to match DB columns
            $row = [
                'id' => $p['id'] ?? null,
                'name' => $p['name'] ?? null,
                'price' => $p['price'] ?? 0,
                'original_price' => $p['originalPrice'] ?? ($p['original_price'] ?? null),
                'image' => $p['image'] ?? null,
                'category' => $p['category'] ?? null,
                'discount' => $p['discount'] ?? 0,
                'type' => $p['type'] ?? null,
                'colors' => isset($p['colors']) ? json_encode($p['colors']) : null,
                'sizes' => isset($p['sizes']) ? json_encode($p['sizes']) : null,
                'description' => $p['description'] ?? null,
                'material' => $p['material'] ?? null,
                'gallery' => isset($p['gallery']) ? json_encode($p['gallery']) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Use upsert by id when id provided, otherwise insert
            if (!empty($row['id'])) {
                // Remove id if null to let DB auto-increment
                DB::table('products')->updateOrInsert(['id' => $row['id']], $row);
            } else {
                DB::table('products')->insert(array_filter($row, function ($k) { return $k !== 'id'; }, ARRAY_FILTER_USE_KEY));
            }
        }

        $this->command->info('Products imported: ' . count($data['products']));
    }
}

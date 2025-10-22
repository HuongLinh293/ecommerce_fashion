<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$outFile = __DIR__ . '/../database/seeders/products.json';

$rows = DB::table('products')->orderBy('id')->get();
$products = [];
foreach ($rows as $r) {
    $p = (array)$r;
    // normalize to seeder shape
    $entry = [];
    $entry['id'] = $p['id'];
    $entry['name'] = $p['name'];
    $entry['price'] = (int)$p['price'];
    $entry['originalPrice'] = isset($p['original_price']) && $p['original_price'] !== null ? (int)$p['original_price'] : (int)$p['price'];
    $entry['image'] = $p['image'] ?? null;
    $entry['category'] = $p['category'] ?? null;
    $entry['discount'] = (int)($p['discount'] ?? 0);
    $entry['type'] = $p['type'] ?? null;
    // attempt to decode JSON columns back to arrays where appropriate
    $entry['colors'] = null;
    if (!empty($p['colors'])) {
        $decoded = @json_decode($p['colors'], true);
        if (json_last_error() === JSON_ERROR_NONE) $entry['colors'] = $decoded;
        else $entry['colors'] = $p['colors'];
    }
    $entry['sizes'] = null;
    if (!empty($p['sizes'])) {
        $decoded = @json_decode($p['sizes'], true);
        if (json_last_error() === JSON_ERROR_NONE) $entry['sizes'] = $decoded;
        else $entry['sizes'] = $p['sizes'];
    }
    $entry['description'] = $p['description'] ?? null;
    $entry['material'] = $p['material'] ?? null;
    $entry['gallery'] = null;
    if (!empty($p['gallery'])) {
        $decoded = @json_decode($p['gallery'], true);
        if (json_last_error() === JSON_ERROR_NONE) $entry['gallery'] = $decoded;
        else $entry['gallery'] = $p['gallery'];
    }

    $products[] = $entry;
}

$out = ['products' => $products];
$json = json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
file_put_contents($outFile, $json);

echo "Wrote seeder file: {$outFile} (" . count($products) . " products)\n";
return 0;

<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$products = DB::table('products');
echo "products_count=" . $products->count() . "\n\n";
$rows = $products->orderBy('updated_at', 'desc')->limit(20)->get();
foreach ($rows as $r) {
    echo $r->id . ' | ' . $r->name . ' | ' . $r->created_at . ' | ' . $r->updated_at . "\n";
}

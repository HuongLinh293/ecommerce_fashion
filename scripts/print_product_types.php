<?php
// scripts/print_product_types.php
// Usage: php scripts/print_product_types.php [category]
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

$category = $argv[1] ?? null;

$query = Product::query();
if ($category) {
    $query->where('category', $category);
}

$types = $query->distinct()->pluck('type')->filter()->values()->toArray();

echo "Product types" . ($category ? " for category={$category}" : "") . ":\n";
foreach ($types as $t) {
    echo "- [" . $t . "]\n";
}

// Also show a few example rows that may contain commas
$examples = Product::query()->when($category, function($q) use ($category){ $q->where('category',$category); })->limit(10)->get(['id','name','type']);

echo "\nExamples (id, name, type):\n";
foreach ($examples as $e) {
    echo "{$e->id} | {$e->name} | [{$e->type}]\n";
}

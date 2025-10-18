<?php
// scripts/test_filter_dress.php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\DB;

$typesFilter = ['dress'];

$q = Product::query();
$q->whereIn(DB::raw('LOWER(`type`)'), $typesFilter);
$rows = $q->get(['id','name','type']);

echo "Matched products: " . $rows->count() . "\n";
$types = [];
foreach($rows as $r){
    echo "{$r->id} | {$r->name} | [{$r->type}]\n";
    $types[] = $r->type;
}
$types = array_values(array_unique($types));
echo "Distinct types matched: " . implode(', ', $types) . "\n";

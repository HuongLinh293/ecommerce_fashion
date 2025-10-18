<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if (!Schema::hasTable('orders')) {
    echo "orders table does not exist\n";
    exit;
}

$cols = Schema::getColumnListing('orders');
echo "orders columns: " . implode(', ', $cols) . "\n";
$sample = DB::table('orders')->orderByDesc('created_at')->first();
if ($sample) {
    echo "Sample order id=" . $sample->id . " | total=" . ($sample->total ?? 'NULL') . " | total_amount=" . ($sample->total_amount ?? 'NULL') . " | payment_method=" . ($sample->payment_method ?? 'NULL') . "\n";
} else {
    echo "No orders found in DB\n";
}

$itemsCount = DB::table('order_items')->count();
echo "order_items count: $itemsCount\n";

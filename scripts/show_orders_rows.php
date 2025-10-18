<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;

$count = Order::count();
echo "orders count: $count\n";
$rows = Order::select('id','status','payment_method','total','created_at')->latest()->take(10)->get();
foreach ($rows as $r) {
    echo $r->id . ' | status=' . $r->status . ' | method=' . ($r->payment_method ?? '[null]') . ' | total=' . ($r->total ?? '[null]') . ' | ' . $r->created_at . "\n";
}

<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;

$orders = Order::with('items')->latest()->take(10)->get();
foreach ($orders as $o) {
    echo "Order id={$o->id} | subtotal={$o->subtotal} | total={$o->total} | total_amount={$o->total_amount}\n";
}

return 0;

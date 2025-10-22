<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;

$orders = Order::with('items')->get();
$count = 0;
foreach ($orders as $o) {
    $o->updateTotal();
    $count++;
}
echo "Recalculated total_amount for {$count} orders\n";
return 0;

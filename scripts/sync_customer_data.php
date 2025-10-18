<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Get all orders
$orders = DB::table('orders')->get();

foreach ($orders as $order) {
    $updates = [
        'customer_name' => $order->shipping_name,
        'customer_phone' => $order->shipping_phone,
        'customer_email' => $order->email,
    ];

    // Only update non-null values
    $updates = array_filter($updates, function($value) {
        return $value !== null;
    });

    if (!empty($updates)) {
        DB::table('orders')
            ->where('id', $order->id)
            ->update($updates);
    }
}

echo "Done updating orders!\n";
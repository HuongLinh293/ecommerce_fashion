<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Str;

$orders = Order::doesntHave('payments')->get();
if ($orders->isEmpty()) {
    echo "No orders without payments found.\n";
    exit;
}

$created = 0;
foreach ($orders as $order) {
    // Create a simple payment record
    // Map order status to payment.status enum (payments.status enum: pending, paid, failed)
    $statusMap = [
        'completed' => 'paid',
        'paid' => 'paid',
        'pending' => 'pending',
        'failed' => 'failed',
        'refunded' => 'paid', // keep as paid for historical
    ];
    $payStatus = $statusMap[$order->status] ?? 'pending';

    $payment = Payment::create([
        'transaction_id' => 'SYNC-' . Str::upper(Str::random(8)),
        'order_id' => $order->id,
        'amount' => $order->total ?? 0,
        'method' => $order->payment_method ?? 'cod',
        'status' => $payStatus,
    ]);
    if ($payment) $created++;
}

echo "Created $created payment(s) from orders.\n";

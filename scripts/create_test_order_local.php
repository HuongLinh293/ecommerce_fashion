<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;

// Create a simple order using existing product(s)
$product = Product::first();
if (!$product) {
    echo "No product available to create test order\n";
    exit(1);
}

$order = Order::create([
    'user_id' => null,
    'customer_name' => 'Test User',
    'customer_phone' => '0123456789',
    'customer_email' => 'test@example.com',
    'shipping_name' => 'Test User',
    'shipping_phone' => '0123456789',
    'shipping_address' => 'Somewhere',
    'status' => 'pending',
    'total' => $product->price,
    'total_amount' => $product->price,
]);

OrderItem::create([
    'order_id' => $order->id,
    'product_id' => $product->id,
    'product_name' => $product->name,
    'product_image' => $product->image,
    'price' => $product->price,
    'quantity' => 1,
    'subtotal' => $product->price,
]);

Payment::create([
    'order_id' => $order->id,
    'method' => 'cod',
    'status' => 'pending',
    'amount' => $order->total_amount,
]);

echo "Created test order id={$order->id} total={$order->total} total_amount={$order->total_amount}\n";
return 0;

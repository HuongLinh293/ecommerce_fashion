<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::find(1);
if ($user) {
    \Illuminate\Support\Facades\Auth::loginUsingId($user->id);
}

$order = \App\Models\Order::latest()->first();
if (!$order) {
    echo "No orders found\n"; exit;
}

$ctrl = new \App\Http\Controllers\Admin\OrderController();
$req = \Illuminate\Http\Request::create('/admin/orders/'.$order->id.'/update-status', 'POST', ['status' => 'completed']);
$resp = $ctrl->updateStatus($req, $order);

$order = \App\Models\Order::with('customer')->find($order->id);
if ($order->customer) {
    echo "Order linked to customer: {$order->customer->id} {$order->customer->email} {$order->customer->phone}\n";
} else {
    echo "Order has no customer linked\n";
}

$cus = \App\Models\Customer::where('email', $order->customer_email)->orWhere('phone', $order->customer_phone)->first();
if ($cus) {
    echo "Customer exists: {$cus->id} | {$cus->name} | {$cus->email} | {$cus->phone}\n";
}

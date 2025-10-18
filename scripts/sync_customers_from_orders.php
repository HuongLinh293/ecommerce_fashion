<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Customer;

$orders = DB::table('orders')
    ->select('customer_name', 'customer_email', 'customer_phone', DB::raw('SUM(total) as total_spent'), DB::raw('COUNT(*) as orders_count'))
    ->groupBy('customer_email', 'customer_phone', 'customer_name')
    ->get();

foreach ($orders as $o) {
    if (empty($o->customer_email) && empty($o->customer_phone)) continue;

    // Prefer matching by email when available, otherwise match by phone.
    if (!empty($o->customer_email)) {
        Customer::updateOrCreate(
            ['email' => $o->customer_email],
            [
                'name' => $o->customer_name ?: 'Khách hàng',
                'phone' => $o->customer_phone ?: null,
                'total_spent' => $o->total_spent ?: 0,
                'is_vip' => false,
            ]
        );
    } else {
        Customer::updateOrCreate(
            ['phone' => $o->customer_phone],
            [
                'name' => $o->customer_name ?: 'Khách hàng',
                'email' => null,
                'total_spent' => $o->total_spent ?: 0,
                'is_vip' => false,
            ]
        );
    }
}

// Update orders to reference the created customers where possible
$customers = DB::table('customers')->get();
foreach ($customers as $c) {
    if (!empty($c->email)) {
        DB::table('orders')->where('customer_email', $c->email)->update(['customer_id' => $c->id]);
    }
    if (!empty($c->phone)) {
        DB::table('orders')->where('customer_phone', $c->phone)->update(['customer_id' => $c->id]);
    }
}

echo "Synced " . $orders->count() . " customer(s) from orders.\n";
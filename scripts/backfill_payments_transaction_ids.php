<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Support\Str;

$payments = Payment::whereNull('transaction_id')
    ->orWhere('transaction_id', '=', '')
    ->get();

if ($payments->isEmpty()) {
    echo "No payments missing transaction_id found." . PHP_EOL;
    exit;
}

$updated = 0;
foreach ($payments as $p) {
    $order = $p->order;
    $tx = 'BACKFILL-' . ($order ? $order->id : 'NOORDER') . '-' . Str::upper(Str::random(6));
    $p->transaction_id = $tx;

    if (empty($p->amount) || $p->amount == 0) {
        $p->amount = $order ? ($order->total ?? 0) : ($p->amount ?? 0);
    }

    if ($p->isDirty()) {
        $p->save();
        $updated++;
    }
}

echo "Updated $updated payment(s) with transaction ids and amounts." . PHP_EOL;
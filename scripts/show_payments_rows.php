<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Payment;

try {
    $count = Payment::count();
    echo "payments count: $count\n";
    $rows = Payment::with('order')->take(10)->get();
    foreach ($rows as $r) {
        echo $r->id . ' | tx=' . ($r->transaction_id ?? '[null]') . ' | order=' . ($r->order_id ?? '[null]') . ' | amount=' . ($r->amount ?? '[null]') . ' | method=' . ($r->method ?? '[null]') . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

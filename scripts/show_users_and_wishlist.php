<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Wishlist;

try {
    $count = User::count();
    echo "users: $count\n";
    if ($count > 0) {
        $u = User::first();
        echo "first user id: " . $u->id . "\n";
        $ids = Wishlist::where('user_id', $u->id)->pluck('product_id')->toArray();
        echo "wishlist ids for user {$u->id}: " . json_encode($ids) . "\n";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}

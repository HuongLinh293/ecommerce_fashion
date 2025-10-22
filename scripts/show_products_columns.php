<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $cols = DB::select('SHOW COLUMNS FROM products');
    foreach ($cols as $c) {
        echo $c->Field . PHP_EOL;
    }

    $hasDeletedAt = collect($cols)->pluck('Field')->contains('deleted_at');
    if ($hasDeletedAt) {
        $count = DB::table('products')->whereNotNull('deleted_at')->count();
        echo "soft_deleted_count=" . $count . PHP_EOL;
    }

    echo "done." . PHP_EOL;
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
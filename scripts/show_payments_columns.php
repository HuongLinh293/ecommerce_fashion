<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $cols = DB::select("SHOW COLUMNS FROM payments");
    foreach ($cols as $c) {
        echo $c->Field . '\n';
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
$rows = DB::select('SHOW TABLES');
foreach ($rows as $r) {
    $vals = get_object_vars($r);
    echo array_values($vals)[0] . "\n";
}

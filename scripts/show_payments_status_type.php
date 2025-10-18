<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$col = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM payments WHERE Field='status'");
echo $col[0]->Type . "\n";

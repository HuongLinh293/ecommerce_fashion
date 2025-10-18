<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$cols = DB::select("SHOW COLUMNS FROM users");
foreach ($cols as $c) {
    echo $c->Field . '\n';
}

$admins = DB::select("select id, email, is_admin from users where is_admin = 1");
if (count($admins) === 0) {
    echo "\nNo is_admin users found.\n";
} else {
    echo "\nAdmin users:\n";
    foreach ($admins as $a) {
        echo $a->id . ' - ' . $a->email . "\n";
    }
}

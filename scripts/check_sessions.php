<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Exception;

echo "DB connection: ";
try {
    DB::connection()->getPdo();
    echo "OK\n";
} catch (Exception $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
    exit(1);
}

if (!Schema::hasTable('sessions')) {
    echo "sessions table does not exist.\n";
    exit(0);
}

$rows = DB::table('sessions')->orderByDesc('last_activity')->limit(10)->get();
$count = DB::table('sessions')->count();

echo "sessions count: $count\n";
if ($rows->isEmpty()) {
    echo "No session rows found.\n";
} else {
    echo "Last sessions:\n";
    foreach ($rows as $r) {
        echo "id=" . ($r->id ?? 'NULL') . " | last_activity=" . date('Y-m-d H:i:s', $r->last_activity ?? 0) . " | payload_len=" . strlen($r->payload ?? '') . "\n";
    }
}

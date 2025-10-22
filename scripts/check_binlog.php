<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

try {
    $res = DB::select("SHOW VARIABLES LIKE 'log_bin'");
    if (count($res) > 0) {
        echo "log_bin=" . $res[0]->Value . PHP_EOL;
    } else {
        echo "log_bin not available\n";
    }

    // Attempt to list binary logs (requires SUPER/REPLICATION CLIENT privileges)
    try {
        $bins = DB::select('SHOW BINARY LOGS');
        if (count($bins) > 0) {
            echo "binary_logs:" . PHP_EOL;
            foreach ($bins as $b) {
                // MySQL returns Log_name and File_size
                $fields = (array)$b;
                echo implode(' | ', $fields) . PHP_EOL;
            }
        } else {
            echo "no binary logs found or insufficient privileges\n";
        }
    } catch (\Exception $e) {
        echo "Cannot list binary logs: " . $e->getMessage() . PHP_EOL;
    }
} catch (\Exception $e) {
    echo "Error querying DB: " . $e->getMessage() . PHP_EOL;
}

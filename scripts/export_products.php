<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$ts = date('YmdHis');
$backupDir = __DIR__ . '/../backups';
if (!is_dir($backupDir)) mkdir($backupDir, 0755, true);

$csvFile = $backupDir . "/products_export_{$ts}.csv";
$sqlFile = $backupDir . "/products_export_{$ts}.sql";

// Get CREATE TABLE
$createRow = DB::select("SHOW CREATE TABLE products");
$createStmt = null;
if ($createRow && isset($createRow[0]->{'Create Table'})) {
    $createStmt = $createRow[0]->{'Create Table'};
} elseif ($createRow && isset($createRow[0]->Create_Table)) {
    $createStmt = $createRow[0]->Create_Table;
}

// Fetch all rows
$rows = DB::table('products')->get();
$cols = [];
if (count($rows) > 0) {
    $cols = array_keys((array)$rows[0]);
}

// Write CSV
$fp = fopen($csvFile, 'w');
if ($fp === false) {
    echo "Failed to open CSV file for writing: {$csvFile}\n";
    exit(1);
}
if ($cols) fputcsv($fp, $cols);
foreach ($rows as $r) {
    $vals = [];
    foreach ($cols as $c) $vals[] = $r->$c;
    fputcsv($fp, $vals);
}
fclose($fp);

// Write SQL
$sf = fopen($sqlFile, 'w');
if ($sf === false) {
    echo "Failed to open SQL file for writing: {$sqlFile}\n";
    exit(1);
}
if ($createStmt) {
    fwrite($sf, "-- Dump of table products\n");
    fwrite($sf, "SET FOREIGN_KEY_CHECKS=0;\n\n");
    fwrite($sf, "DROP TABLE IF EXISTS `products`;\n\n");
    fwrite($sf, $createStmt . ";\n\n");
    fwrite($sf, "SET FOREIGN_KEY_CHECKS=1;\n\n");
}

if ($cols && count($rows) > 0) {
    foreach ($rows as $r) {
        $values = [];
        foreach ($cols as $c) {
            $v = $r->$c;
            if (is_null($v)) {
                $values[] = 'NULL';
            } else {
                // escape single quotes
                $escaped = str_replace("'", "\\'", (string)$v);
                $values[] = "'" . $escaped . "'";
            }
        }
        $line = 'INSERT INTO `products` (`' . implode('`,`', $cols) . '`) VALUES (' . implode(',', $values) . ');\n';
        fwrite($sf, $line);
    }
}

fclose($sf);

echo "Wrote CSV: {$csvFile}\n";
echo "Wrote SQL: {$sqlFile}\n";

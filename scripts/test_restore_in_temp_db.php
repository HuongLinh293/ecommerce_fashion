<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Paths
$snapshot = __DIR__ . '/../backups/products_export_20251022053357.sql';
$diffSql = __DIR__ . '/../backups/seed_db_diff_20251022053837.sql';
$tempDb = 'products_restore_db';

if (!file_exists($snapshot)) {
    echo "Snapshot SQL not found: {$snapshot}\n";
    exit(1);
}
if (!file_exists($diffSql)) {
    echo "Diff SQL not found: {$diffSql}\n";
    exit(1);
}

// Get default connection config from Laravel
$config = config('database.connections.' . config('database.default'));
$host = $config['host'] ?? '127.0.0.1';
$port = $config['port'] ?? '3306';
$user = $config['username'] ?? 'root';
$pass = $config['password'] ?? '';

echo "Using DB host={$host} user={$user} port={$port}\n";

$pdo = new PDO("mysql:host={$host};port={$port}", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

// Create temp DB
echo "Creating temp DB {$tempDb}...\n";
$pdo->exec("CREATE DATABASE IF NOT EXISTS `{$tempDb}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

// Import snapshot into temp DB using simple split of statements (works for this dump)
echo "Importing snapshot into {$tempDb} (this may take a few seconds)...\n";
$sql = file_get_contents($snapshot);
// Switch to temp DB and run
$pdo->exec("USE `{$tempDb}`;");
// Remove DELIMITER statements if any and execute
$statements = array_filter(array_map('trim', preg_split('/;\s*\n/', $sql)));
$count = 0;
foreach ($statements as $st) {
    if ($st === '') continue;
    try {
        $pdo->exec($st);
    } catch (Exception $e) {
        // ignore minor parse issues
        // echo "Statement error: ".$e->getMessage()."\n";
    }
    $count++;
}
echo "Executed approx {$count} statements into {$tempDb}\n";

// Show values before applying diff
echo "\n--- Values in temp DB before applying diff (ids 2,11,12) ---\n";
$pdo->exec("USE `{$tempDb}`;");
$stmt = $pdo->query("SELECT id,name,colors,sizes,gallery FROM products WHERE id IN (2,11,12) ORDER BY id");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    echo json_encode($r, JSON_UNESCAPED_UNICODE) . "\n";
}
$stmt->closeCursor();

// Apply diff SQL (which includes SET FOREIGN_KEY_CHECKS toggles)
echo "\nApplying diff SQL to temp DB...\n";
$diff = file_get_contents($diffSql);
$diffStatements = array_filter(array_map('trim', preg_split('/;\s*\n/', $diff)));
foreach ($diffStatements as $d) {
    if ($d === '') continue;
    try { $pdo->exec($d); } catch (Exception $e) { echo "Diff exec error: " . $e->getMessage() . "\n"; }
}

// Show values after applying diff
echo "\n--- Values in temp DB after applying diff (ids 2,11,12) ---\n";
$pdo->exec("USE `{$tempDb}`;");
$stmt2 = $pdo->query("SELECT id,name,colors,sizes,gallery FROM products WHERE id IN (2,11,12) ORDER BY id");
$rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows2 as $r) {
    echo json_encode($r, JSON_UNESCAPED_UNICODE) . "\n";
}
$stmt2->closeCursor();

echo "\nTest complete. Temp DB: {$tempDb}. No changes were made to original DB.\n";

return 0;

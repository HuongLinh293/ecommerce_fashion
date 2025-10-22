<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

$tempDb = 'products_restore_db';
$config = config('database.connections.' . config('database.default'));
$host = $config['host'] ?? '127.0.0.1';
$port = $config['port'] ?? '3306';
$user = $config['username'] ?? 'root';
$pass = $config['password'] ?? '';
$pdo = new PDO("mysql:host={$host};port={$port}", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

try {
    $pdo->exec("USE `{$tempDb}`;");
    $row = $pdo->query("SHOW CREATE TABLE products")->fetch(PDO::FETCH_ASSOC);
    echo "SHOW CREATE TABLE products:\n";
    echo $row['Create Table'] . "\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

$stmt = $pdo->query("SELECT COLUMN_NAME,COLUMN_TYPE,IS_NULLABLE,COLUMN_DEFAULT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '{$tempDb}' AND TABLE_NAME='products'");
$cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Columns in products:\n";
foreach ($cols as $c) echo json_encode($c) . "\n";

// Show CHECK constraints (MySQL 8+ supports information_schema CHECK_CONSTRAINTS but may be empty)
try {
    $cc = $pdo->query("SELECT CONSTRAINT_NAME,CHECK_CLAUSE FROM information_schema.CHECK_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = '{$tempDb}'")->fetchAll(PDO::FETCH_ASSOC);
    if ($cc) {
        echo "\nCHECK constraints:\n";
        foreach ($cc as $r) echo json_encode($r) . "\n";
    }
} catch (Exception $e) {
    // ignore if not supported
}

return 0;

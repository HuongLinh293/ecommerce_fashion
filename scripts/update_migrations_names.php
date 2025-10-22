<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$db = getenv('DB_DATABASE') ?: 'vivillan';
$user = getenv('DB_USERNAME') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';

dsn: $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    echo "DB connect failed: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

$mapping = [
    '000000_create_products_table' => '2024_01_01_000000_create_products_table',
    '2025_10_17_161300_create_customers_table' => '2025_10_17_161301_create_customers_table_alt',
    '2025_10_18_000001_ensure_is_admin_in_users' => '2025_10_18_000005_ensure_is_admin_in_users',
];

try {
    $pdo->beginTransaction();
    foreach ($mapping as $old => $new) {
        $stmt = $pdo->prepare('SELECT COUNT(*) as cnt FROM migrations WHERE migration = ?');
        $stmt->execute([$old]);
        $row = $stmt->fetch();
        if ($row && $row['cnt'] > 0) {
            $update = $pdo->prepare('UPDATE migrations SET migration = ? WHERE migration = ?');
            $update->execute([$new, $old]);
            echo "Updated: {$old} -> {$new}\n";
        } else {
            echo "No row found for: {$old} (skipped)\n";
        }
    }
    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Failed to update migrations table: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

// Show resulting entries
$placeholders = implode(',', array_fill(0, count($mapping), '?'));
$values = array_values($mapping);
$stmt = $pdo->prepare("SELECT migration, batch FROM migrations WHERE migration IN ($placeholders) ORDER BY migration");
$stmt->execute($values);
$rows = $stmt->fetchAll();
if (!$rows) {
    echo "No updated rows found in migrations table.\n";
} else {
    foreach ($rows as $r) {
        echo $r['migration'] . ' | batch:' . $r['batch'] . PHP_EOL;
    }
}

echo "Done.\n";

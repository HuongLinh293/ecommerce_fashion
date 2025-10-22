<?php
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();
$pdo = new PDO('mysql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->query('SELECT COUNT(*) AS cnt FROM products');
$cnt = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
echo "products_count=".intval($cnt)."\n\n";

$stmt = $pdo->query('SELECT id, name, created_at, updated_at FROM products ORDER BY updated_at DESC LIMIT 20');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    echo sprintf("%s | %s | created: %s | updated: %s\n", $r['id'], $r['name'], $r['created_at'], $r['updated_at']);
}

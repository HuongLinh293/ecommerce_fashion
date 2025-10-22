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
$pdo = new PDO("mysql:host={$host};port={$port};dbname={$tempDb}", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

echo "Connecting to temp DB {$tempDb}...\n";

// Show current values
echo "\nBefore updates:\n";
$stmt = $pdo->query("SELECT id,name,colors,sizes,gallery FROM products WHERE id IN (2,11,12) ORDER BY id");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) echo json_encode($r, JSON_UNESCAPED_UNICODE) . "\n";

// Prepare corrected updates (use JSON_QUOTE for string-like fields and raw JSON for arrays)
$updates = [
    "UPDATE products SET colors = JSON_QUOTE('Black, Navy, Charcoal'), sizes = JSON_QUOTE('S, M, L, XL') WHERE id = 2;",
    "UPDATE products SET colors = JSON_QUOTE('Floral Blue, Floral Pink'), sizes = JSON_QUOTE('S, M, L'), gallery = '[\"/assets/products/gallery_68f25374569a1.png\",\"/assets/products/gallery_68f2537457217.png\",\"/assets/products/gallery_68f2537457cfd.png\",\"/assets/products/gallery_68f2537458163.png\"]' WHERE id = 11;",
    "UPDATE products SET colors = JSON_QUOTE('Black, Navy, Wine Red'), sizes = JSON_QUOTE('S, M, L'), gallery = '[\"/assets/products/gallery_68f252f4eefe4.png\",\"/assets/products/gallery_68f252f4efbe1.png\",\"/assets/products/gallery_68f252f4f0262.png\",\"/assets/products/gallery_68f252f4f06b7.png\"]' WHERE id = 12;",
];

foreach ($updates as $u) {
    try {
        $pdo->exec($u);
        echo "Executed: " . $u . "\n";
    } catch (Exception $e) {
        echo "Update error: " . $e->getMessage() . "\n";
    }
}

// Show values after updates
echo "\nAfter updates:\n";
$stmt2 = $pdo->query("SELECT id,name,colors,sizes,gallery FROM products WHERE id IN (2,11,12) ORDER BY id");
$rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows2 as $r) echo json_encode($r, JSON_UNESCAPED_UNICODE) . "\n";

echo "\nDone.\n";
return 0;

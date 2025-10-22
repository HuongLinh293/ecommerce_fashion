<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$ts = date('YmdHis');
$backupDir = __DIR__ . '/../backups';
if (!is_dir($backupDir)) mkdir($backupDir, 0755, true);

$jsonFile = __DIR__ . '/../database/seeders/products.json';
if (!file_exists($jsonFile)) {
    echo "Seeder file not found: {$jsonFile}\n";
    exit(1);
}

$json = file_get_contents($jsonFile);
$data = json_decode($json, true);
if (!isset($data['products']) || !is_array($data['products'])) {
    echo "Invalid seeder JSON format\n";
    exit(1);
}

$seedProducts = $data['products'];

// Fetch DB rows keyed by id
$dbRows = DB::table('products')->get();
$dbById = [];
foreach ($dbRows as $r) {
    $dbById[$r->id] = (array)$r;
}

// Helper: normalize seeder product to DB column names
function normalizeSeeder(array $p): array {
    // map known keys
    $out = [];
    $out['id'] = isset($p['id']) ? $p['id'] : null;
    $out['name'] = $p['name'] ?? null;
    $out['price'] = $p['price'] ?? null;
    $out['original_price'] = $p['originalPrice'] ?? ($p['original_price'] ?? null);
    $out['image'] = $p['image'] ?? null;
    $out['category'] = $p['category'] ?? null;
    $out['discount'] = $p['discount'] ?? 0;
    $out['type'] = $p['type'] ?? null;
    // colors and sizes: store as JSON if array, else string
    if (isset($p['colors'])) {
        $out['colors'] = is_array($p['colors']) ? json_encode($p['colors'], JSON_UNESCAPED_UNICODE) : $p['colors'];
    } else {
        $out['colors'] = null;
    }
    if (isset($p['sizes'])) {
        $out['sizes'] = is_array($p['sizes']) ? json_encode($p['sizes'], JSON_UNESCAPED_UNICODE) : $p['sizes'];
    } else {
        $out['sizes'] = null;
    }
    $out['description'] = $p['description'] ?? null;
    $out['material'] = $p['material'] ?? null;
    // gallery: array or JSON string -> store as JSON string
    if (isset($p['gallery'])) {
        if (is_array($p['gallery'])) $out['gallery'] = json_encode($p['gallery'], JSON_UNESCAPED_UNICODE);
        else $out['gallery'] = $p['gallery'];
    } else {
        $out['gallery'] = null;
    }
    // default other columns to null; leave created_at/updated_at alone
    $out['status'] = $p['status'] ?? null;
    $out['stock'] = $p['stock'] ?? null;
    $out['stock_quantity'] = $p['stock_quantity'] ?? null;
    $out['is_active'] = $p['is_active'] ?? 1;
    return $out;
}

$diffCsv = $backupDir . "/seed_db_diff_{$ts}.csv";
$diffSql = $backupDir . "/seed_db_diff_{$ts}.sql";

$fhCsv = fopen($diffCsv, 'w');
$fhSql = fopen($diffSql, 'w');
if (!$fhCsv || !$fhSql) {
    echo "Failed to open output files in {$backupDir}\n";
    exit(1);
}

fputcsv($fhCsv, ['id','status','diff_fields','db_values','seed_values']);

$inserts = [];
$updates = [];

$seenIds = [];
foreach ($seedProducts as $sp) {
    $seed = normalizeSeeder($sp);
    $id = $seed['id'];
    $seenIds[$id] = true;
    if (!isset($dbById[$id])) {
        // Missing in DB -> prepare INSERT
        $cols = [];
        $vals = [];
        $allCols = ['id','name','price','original_price','image','category','discount','type','colors','sizes','description','material','gallery','status','stock','stock_quantity','is_active'];
        foreach ($allCols as $c) {
            $cols[] = "`{$c}`";
            $v = array_key_exists($c, $seed) ? $seed[$c] : null;
            if (is_null($v)) $vals[] = 'NULL';
            else {
                $escaped = str_replace("'","\\'", (string)$v);
                $vals[] = "'{$escaped}'";
            }
        }
        $sql = 'INSERT INTO `products` (' . implode(',', $cols) . ') VALUES (' . implode(',', $vals) . ');';
        $inserts[] = $sql;
        fputcsv($fhCsv, [$id,'missing',implode(';', $allCols),'','']);
    } else {
        $db = $dbById[$id];
        // Compare selected fields
        $checkFields = ['name','price','original_price','image','category','discount','type','colors','sizes','description','material','gallery','is_active'];
        $diffs = [];
        $dbVals = [];
        $seedVals = [];
        foreach ($checkFields as $f) {
            $dbv = array_key_exists($f, $db) ? $db[$f] : null;
            $seedv = array_key_exists($f, $seed) ? $seed[$f] : null;
            // normalize JSON strings for comparison (if looks like JSON)
            if (is_string($dbv) && ($dbv !== null) && ($dbv !== '') && (@json_decode($dbv) !== null)) {
                $db_cmp = json_encode(json_decode($dbv), JSON_UNESCAPED_UNICODE);
            } else {
                $db_cmp = $dbv;
            }
            if (is_string($seedv) && ($seedv !== null) && ($seedv !== '') && (@json_decode($seedv) !== null)) {
                $seed_cmp = json_encode(json_decode($seedv), JSON_UNESCAPED_UNICODE);
            } else {
                $seed_cmp = $seedv;
            }
            if ((string)$db_cmp !== (string)$seed_cmp) {
                $diffs[] = $f;
                $dbVals[$f] = $dbv;
                $seedVals[$f] = $seedv;
            }
        }
        if (count($diffs) > 0) {
            // Prepare UPDATE
            $sets = [];
            foreach ($seedVals as $k => $v) {
                if (is_null($v)) $sets[] = "`{$k}`=NULL";
                else {
                    $escaped = str_replace("'","\\'", (string)$v);
                    $sets[] = "`{$k}`='{$escaped}'";
                }
            }
            $sql = 'UPDATE `products` SET ' . implode(',', $sets) . " WHERE `id`='{$id}';";
            $updates[] = $sql;
            fputcsv($fhCsv, [$id,'modified',implode(';',$diffs),json_encode($dbVals, JSON_UNESCAPED_UNICODE),json_encode($seedVals, JSON_UNESCAPED_UNICODE)]);
        }
    }
}

// Find DB-only rows not present in seeder
$extra = [];
foreach ($dbById as $dbid => $row) {
    if (!isset($seenIds[$dbid])) $extra[] = $dbid;
}
if (count($extra) > 0) {
    fputcsv($fhCsv, ['','db_only',implode(';',$extra),'','']);
}

// Write SQL file
fwrite($fhSql, "-- SQL diff generated by compare_seed_with_db.php\n");
fwrite($fhSql, "SET FOREIGN_KEY_CHECKS=0;\n\n");
foreach ($inserts as $i) fwrite($fhSql, $i . "\n");
foreach ($updates as $u) fwrite($fhSql, $u . "\n");
fwrite($fhSql, "\nSET FOREIGN_KEY_CHECKS=1;\n");

fclose($fhCsv);
fclose($fhSql);

$summary = [];
$summary[] = 'Missing rows to insert: ' . count($inserts);
$summary[] = 'Modified rows to update: ' . count($updates);
$summary[] = 'DB-only rows not in seeder: ' . count($extra);

echo implode("\n", $summary) . "\n";
echo "Wrote diff CSV: {$diffCsv}\n";
echo "Wrote diff SQL: {$diffSql}\n";

return 0;

?>

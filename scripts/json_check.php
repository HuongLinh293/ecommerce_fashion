<?php
$path = __DIR__ . '/../database/seeders/products.json';
$s = file_get_contents($path);
$json = json_decode($s, true);
var_dump(json_last_error_msg());
if ($json === null) {
    echo "--- preview (first 400 chars) ---\n";
    echo substr($s,0,400) . "\n";
}

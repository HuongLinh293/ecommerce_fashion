<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\View;
use App\Http\Controllers\Admin\CustomerController;

try {
    $ctrl = new CustomerController();
    $resp = $ctrl->index();
    $data = $resp->getData();
    $html = View::make('admin.customers.index', $data)->render();
    echo "Rendered length: " . strlen($html) . "\n";
} catch (Exception $e) {
    echo "Render error: " . $e->getMessage() . "\n";
}

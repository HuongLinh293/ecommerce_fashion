<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Admin\CustomerController;

try {
    $controller = new CustomerController();
    $response = $controller->index();
    if (method_exists($response, 'getData')) {
        $data = $response->getData();
        echo "View data keys: " . implode(',', array_keys($data)) . "\n";
        if (isset($data['customers'])) {
            echo "Customers count (per page): " . (is_object($data['customers']) && method_exists($data['customers'], 'total') ? $data['customers']->total() : count($data['customers'])) . "\n";
        }
    } else {
        echo "Response is not a view or cannot extract data.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

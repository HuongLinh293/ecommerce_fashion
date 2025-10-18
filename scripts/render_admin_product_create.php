<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\View\View as ViewContract;

try {
    // Provide an empty errors bag to emulate typical HTTP request shared data
    View::share('errors', new \Illuminate\Support\ViewErrorBag());
    $html = View::make('admin.products.create')->render();
    echo 'Rendered length: ' . strlen($html) . PHP_EOL;
} catch (Exception $e) {
    echo 'Render error: ' . $e->getMessage() . PHP_EOL;
}

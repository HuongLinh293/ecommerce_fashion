<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ctrl = new \App\Http\Controllers\Admin\DashboardController();
$user = \App\Models\User::find(1);
if ($user) {
    \Illuminate\Support\Facades\Auth::loginUsingId($user->id);
}

// create a request with optional period param (default to 'day')
$req = \Illuminate\Http\Request::create('/admin/dashboard', 'GET', ['period' => 'day']);
$response = $ctrl->index($req);
$data = $response->getData();
if (isset($data['recentOrders'])) {
    foreach ($data['recentOrders'] as $o) {
        echo is_object($o) ? 'OBJ' : 'ARR';
        echo ' order_number: ' . ($o->order_number ?? '[none]') . PHP_EOL;
    }
}

// Try rendering view (share empty errors)
\Illuminate\Support\Facades\View::share('errors', new \Illuminate\Support\ViewErrorBag());
echo 'Rendering dashboard...\n';
echo strlen(\Illuminate\Support\Facades\View::make('admin.dashboard', $data)->render()) . "\n";

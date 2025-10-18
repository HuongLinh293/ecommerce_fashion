<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Use fully-qualified names to avoid import issues in scripts
$ctrl = new \App\Http\Controllers\Admin\PaymentController();
$user = \App\Models\User::find(1);
if ($user) {
    \Illuminate\Support\Facades\Auth::loginUsingId($user->id);
}

$req = \Illuminate\Http\Request::create('/admin/payments', 'GET', []);
$response = $ctrl->index($req);
$data = $response->getData();

echo "Controller returned: \n";
if (isset($data['payments'])) {
    echo "payments instanceof: " . (is_object($data['payments']) ? get_class($data['payments']) : gettype($data['payments'])) . "\n";
    echo "payments total: " . (method_exists($data['payments'], 'total') ? $data['payments']->total() : (is_array($data['payments']) ? count($data['payments']) : 'N/A')) . "\n";
}
if (isset($data['stats'])) {
    echo "stats keys: " . implode(',', array_keys((array)$data['stats'])) . "\n";
}
if (isset($data['recentPayments'])) {
    echo "recentPayments count: " . count($data['recentPayments']) . "\n";
    foreach ($data['recentPayments'] as $p) {
        echo "- " . ($p->transaction_id ?? '[no id]') . " | amount=" . ($p->amount ?? '[no amount]') . " | order=" . ($p->order->id ?? '[no order]') . "\n";
    }
}

// Try rendering view
\Illuminate\Support\Facades\View::share('errors', new \Illuminate\Support\ViewErrorBag());
echo "Rendering payments view...\n";
echo strlen(\Illuminate\Support\Facades\View::make('admin.payments.index', $data)->render()) . "\n";

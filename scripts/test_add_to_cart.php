<?php
// Test adding product to cart programmatically
require __DIR__ . '/../vendor/autoload.php';

// Boot Laravel framework
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a request similar to POST /cart/add
$request = Illuminate\Http\Request::create('/cart/add', 'POST', [
    'product_id' => 1,
    'name' => 'Test Product',
    'price' => 100000,
    'quantity' => 2,
    'size' => 'M',
    'color' => 'Black',
    'image' => '/assets/products/test.jpg'
]);
// Let the HTTP kernel handle the request (runs middleware, session, etc.)
$app['session']->start();
$csrf = $app['session']->token();
$request->headers->set('X-CSRF-TOKEN', $csrf);
$request->headers->set('X-Requested-With', 'XMLHttpRequest');

$response = $kernel->handle($request);

echo "Response status: " . $response->getStatusCode() . PHP_EOL;
echo (string) $response->getContent() . PHP_EOL;

// Terminate the kernel to persist session (if needed)
$kernel->terminate($request, $response);

// Inspect cart content via facade
$cart = Darryldecode\Cart\Facades\CartFacade::getContent();
echo "Cart items count: " . count($cart) . PHP_EOL;
foreach ($cart as $item) {
    echo "- id={$item->id} name={$item->name} qty={$item->quantity}\n";
}


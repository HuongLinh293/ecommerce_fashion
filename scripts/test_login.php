<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

$creds = ['email' => 'admin@gmail.com', 'password' => '123456'];
$result = Auth::attempt($creds);
var_dump($result);
if ($result) {
    echo 'User ID: ' . Auth::id() . PHP_EOL;
    Auth::logout();
}

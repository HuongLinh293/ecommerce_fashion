<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

$email = 'admin@gmail.com';
$exists = DB::table('users')->where('email', $email)->first();
if ($exists) {
    DB::table('users')->where('email', $email)->update([
        'name' => 'Administrator',
        'password' => Hash::make('123456'),
        'is_admin' => 1,
        'updated_at' => now(),
    ]);
    echo "Updated admin@gmail.com\n";
} else {
    DB::table('users')->insert([
        'name' => 'Administrator',
        'email' => $email,
        'password' => Hash::make('123456'),
        'is_admin' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "Created admin@gmail.com\n";
}

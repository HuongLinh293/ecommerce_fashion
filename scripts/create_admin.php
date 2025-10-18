<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$adminEmail = 'admin@local.test';
$user = DB::table('users')->where('email', $adminEmail)->first();
if ($user) {
    DB::table('users')->where('id', $user->id)->update(['is_admin' => 1]);
    echo "Updated existing user {$adminEmail} to is_admin=1\n";
    exit;
}

// If no user with that email, try to find any user and make admin
$any = DB::table('users')->first();
if ($any) {
    DB::table('users')->where('id', $any->id)->update(['is_admin' => 1]);
    echo "Set existing user {$any->email} as admin\n";
    exit;
}

// Otherwise create new admin user
$id = DB::table('users')->insertGetId([
    'name' => 'Admin',
    'email' => $adminEmail,
    'password' => Hash::make('password'),
    'is_admin' => 1,
    'created_at' => now(),
    'updated_at' => now(),
]);

echo "Created admin user {$adminEmail} with id {$id} (password: password)\n";

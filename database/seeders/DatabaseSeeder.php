<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ✅ Gọi seeder sản phẩm (nếu có)
        $this->call([
            ProductSeeder::class,
        ]);

        // ✅ Tạo tài khoản admin mặc định
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // Nếu đã có email này thì update
            [
                'name' => 'Administrator',
                'password' => Hash::make('123456'), // Mật khẩu mặc định
                'is_admin' => true,
            ]
        );
    }
}
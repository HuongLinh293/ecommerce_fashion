<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- THÊM DÒNG NÀY

class User extends Authenticatable
{
    use HasFactory, Notifiable; // <--- THÊM HasFactory VÀO ĐÂY

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin', // <--- Nên thêm luôn để seed được admin
    ];

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }
        public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
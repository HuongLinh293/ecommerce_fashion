<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'is_vip',
        'total_spent',
    ];

    // Quan hệ với đơn hàng (nếu có)
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
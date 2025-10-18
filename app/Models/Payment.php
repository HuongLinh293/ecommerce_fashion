<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'order_id',
        'amount',
        'method',
        'status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Gán nhãn hiển thị cho trạng thái
    public function getStatusLabelAttribute()
    {
        // Map both 'paid' and 'completed' to the same human-friendly label since
        // older scripts used 'paid' while newer code prefers 'completed'.
        return match ($this->status) {
            'completed', 'paid' => 'Hoàn tất',
            'pending' => 'Đang xử lý',
            'failed' => 'Thất bại',
            'refunded' => 'Hoàn tiền',
            default => ucfirst($this->status),
        };
    }
}
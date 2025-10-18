<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_image',
        'price',
        'quantity',
        'subtotal',
        'size',
        'color',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    protected static function booted()
    {
        static::saving(function ($item) {
            $item->subtotal = $item->price * $item->quantity;
        });

        static::saved(function ($item) {
            $item->order->updateTotal();
        });

        static::deleted(function ($item) {
            $item->order->updateTotal();
        });
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class);
    }
    

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'    => 'Chờ xác nhận',
            'processing' => 'Đang xử lý',
            'shipped'    => 'Đang giao hàng',
            'completed'  => 'Hoàn thành',
            'cancelled'  => 'Đã hủy',
            default      => ucfirst($this->status),
        };
    }
    // Consolidated fillable fields used across controllers and migrations.
    protected $fillable = [
        'user_id',
        'customer_id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'email',
        'status',
        'payment_method',
        'total',
        'shipping_fee',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function updateTotal()
    {
        $this->total = $this->items()->sum('subtotal');
        $this->save();
    }

    /**
     * Upsert or create a Customer record for this order and link the order.
     * Returns the Customer model or null if none created.
     */
    public function upsertCustomerRecord()
    {
        // Prefer explicit customer_email/phone on the order; fallback to linked user
        $email = $this->customer_email ?: ($this->user->email ?? null);
        $phone = $this->customer_phone ?: null;
        $name = $this->customer_name ?: ($this->shipping_name ?? ($this->user->name ?? 'Khách hàng'));

        if (!$email && !$phone) {
            return null;
        }

        // Prepare lookup keys: prefer email lookup when available, else phone
        $lookup = $email ? ['email' => $email] : ['phone' => $phone];

        // Compute total_spent robustly: if a matching customer exists, sum orders by customer_id;
        // otherwise sum by email fallback and include this order total.
        $existingCustomer = null;
        if ($email) {
            $existingCustomer = \App\Models\Customer::where('email', $email)->first();
        }
        if (!$existingCustomer && $phone) {
            $existingCustomer = \App\Models\Customer::where('phone', $phone)->first();
        }

        if ($existingCustomer) {
            $totalSpent = DB::table('orders')->where('customer_id', $existingCustomer->id)->sum('total');
        } else {
            // no linked customer yet; approximate via orders with same email or use this order total
            $totalSpent = $email ? DB::table('orders')->where('customer_email', $email)->sum('total') : ($this->total ?? 0);
            $totalSpent = $totalSpent ?: ($this->total ?? 0);
        }

        $customer = \App\Models\Customer::updateOrCreate(
            $lookup,
            [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'total_spent' => $totalSpent,
            ]
        );

        if ($customer && $customer->id) {
            // Ensure order has customer_id and also persist the customer_* fields on order
            $updated = false;
            if ($this->customer_id !== $customer->id) {
                $this->customer_id = $customer->id;
                $updated = true;
            }
            if (empty($this->customer_name) && $name) {
                $this->customer_name = $name;
                $updated = true;
            }
            if (empty($this->customer_email) && $email) {
                $this->customer_email = $email;
                $updated = true;
            }
            if (empty($this->customer_phone) && $phone) {
                $this->customer_phone = $phone;
                $updated = true;
            }

            if ($updated) {
                $this->save();
            }
        }

        return $customer;
    }

    /**
     * Accessor: provide total_amount even if DB column missing (fallback to total)
     */
    public function getTotalAmountAttribute($value)
    {
        return $value ?? $this->total;
    }

    /**
     * Accessor: provide order_number (fallback to id when missing)
     */
    public function getOrderNumberAttribute($value)
    {
        return $value ?? $this->id;
    }
}

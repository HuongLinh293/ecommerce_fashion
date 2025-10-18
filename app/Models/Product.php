<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'original_price',
        'category',
        'type',
        'discount',
        'image',
        'gallery',
        'colors',
        'sizes',
        'description',
        'material',
        'stock_quantity',
        'is_active',
    ];

    protected $casts = [
        'colors' => 'array',
        'sizes' => 'array',
        'gallery' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Return a public URL for the main image. Supports /assets/, /storage/, http, or plain storage filenames.
     */
    public function getImageUrlAttribute()
    {
        $img = $this->image ?? '';
        if (!$img) return '';

        if (str_starts_with($img, '/assets/')) {
            return asset(ltrim($img, '/'));
        }

        if (str_starts_with($img, 'http')) {
            return $img;
        }

        if (str_starts_with($img, '/storage/')) {
            return asset(ltrim($img, '/'));
        }

        // Fallback: assume it's a storage path without leading '/storage/'
        return asset('storage/' . ltrim($img, '/'));
    }

    /**
     * Return an array of public URLs for gallery images.
     */
    public function getGalleryUrlsAttribute(): array
    {
        $gallery = $this->gallery;

        // gallery may be stored as JSON string in some records — normalize
        if (is_string($gallery)) {
            $decoded = json_decode($gallery, true);
            if (is_array($decoded)) {
                $gallery = $decoded;
            } else {
                $gallery = array_filter(array_map('trim', explode(',', $gallery)));
            }
        }

        if (!is_array($gallery)) {
            return [];
        }

        return array_values(array_map(function ($img) {
            if (!$img) return '';
            if (str_starts_with($img, '/assets/')) return asset(ltrim($img, '/'));
            if (str_starts_with($img, 'http')) return $img;
            if (str_starts_with($img, '/storage/')) return asset(ltrim($img, '/'));
            return asset('storage/' . ltrim($img, '/'));
        }, $gallery));
    }

    /**
     * Normalize sizes to array regardless of storage format (array, JSON string, CSV)
     */
    public function getParsedSizesAttribute(): array
    {
        $sizes = $this->sizes;
        if (is_string($sizes)) {
            $decoded = json_decode($sizes, true);
            if (is_array($decoded)) return $decoded;
            return array_filter(array_map('trim', explode(',', $sizes)));
        }
        if (is_array($sizes)) return $sizes;
        return [];
    }

    /**
     * Normalize colors to array regardless of storage format (array, JSON string, CSV)
     */
    public function getParsedColorsAttribute(): array
    {
        $colors = $this->colors;
        if (is_string($colors)) {
            $decoded = json_decode($colors, true);
            if (is_array($decoded)) return $decoded;
            return array_filter(array_map('trim', explode(',', $colors)));
        }
        if (is_array($colors)) return $colors;
        return [];
    }

    /**
     * Normalize gallery to array of raw paths (not URLs).
     */
    public function getParsedGalleryAttribute(): array
    {
        $gallery = $this->gallery;
        if (is_string($gallery)) {
            $decoded = json_decode($gallery, true);
            if (is_array($decoded)) return $decoded;
            return array_filter(array_map('trim', explode(',', $gallery)));
        }
        if (is_array($gallery)) return $gallery;
        return [];
    }

    /** Scope: Chỉ sản phẩm đang hoạt động */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** Scope: Chỉ sản phẩm còn hàng */
    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    /** Liên kết với OrderItem */
    public function orderItems()
    {
        return $this->hasMany(\App\Models\OrderItem::class, 'product_id');
    }
}
<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    protected string $sessionKey = 'shopping_cart';

    public function get(): array
    {
        return session($this->sessionKey, [
            'items' => [],
            'totalItems' => 0,
            'totalPrice' => 0,
        ]);
    }

    protected function save(array $cart): void
    {
        $cart['totalItems'] = collect($cart['items'])->sum('quantity');
        $cart['totalPrice'] = collect($cart['items'])->sum(fn($i) => $i['price'] * $i['quantity']);
        session([$this->sessionKey => $cart]);
    }

    public function add(int $productId, int $quantity = 1, ?string $color = null, ?string $size = null): array
    {
        $cart = $this->get();
        $product = Product::find($productId);

        if (!$product) {
            throw new \Exception('Sản phẩm không tồn tại.');
        }

        $price = $product->price ?? $product->original_price ?? 0;
        $id = $product->id;

        if (isset($cart['items'][$id])) {
            $cart['items'][$id]['quantity'] += $quantity;
        } else {
            $cart['items'][$id] = [
                'id' => $id,
                'name' => $product->name,
                'price' => $price,
                'quantity' => $quantity,
                'image' => $product->image,
                'color' => $color,
                'size' => $size,
            ];
        }

        $this->save($cart);
        return $cart;
    }

    public function update(int $productId, int $quantity): array
    {
        $cart = $this->get();

        if (isset($cart['items'][$productId])) {
            $cart['items'][$productId]['quantity'] = max(1, $quantity);
            $this->save($cart);
        }

        return $cart;
    }

    public function remove(int $productId): array
    {
        $cart = $this->get();

        if (isset($cart['items'][$productId])) {
            unset($cart['items'][$productId]);
            $this->save($cart);
        }

        return $cart;
    }

    public function clear(): void
    {
        session()->forget($this->sessionKey);
    }
}
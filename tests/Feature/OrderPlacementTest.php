<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;

class OrderPlacementTest extends TestCase
{
    use RefreshDatabase;

    public function test_session_cart_place_order_persists_options()
    {
        // Create a product
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 1000,
        ]);

        // Put a session cart with attributes shape used by the site
        $cart = [
            $product->id => [
                'name' => $product->name,
                'price' => 1000,
                'quantity' => 2,
                'size' => 'M',
                'color' => 'Red',
                'image' => '/assets/products/test.png',
            ],
        ];

        $response = $this->withSession(['cart' => $cart])->post('/checkout', [
            'shipping_name' => 'Tester',
            'shipping_phone' => '0123456789',
            'shipping_address' => '123 Test St',
            'payment_method' => 'cod',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'shipping_name' => 'Tester',
        ]);

        $this->assertDatabaseHas('order_items', [
            'size' => 'M',
            'color' => 'Red',
        ]);
    }
}

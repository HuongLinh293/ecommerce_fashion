<?php

namespace Tests\Feature;

use Tests\TestCase;
use Darryldecode\Cart\Cart as CartService;

class CartAddTest extends TestCase
{

    /** @test */
    public function adding_product_with_quantity_is_honored()
    {
        // Create a fake product array (no DB required for Cart)
        $payload = [
            'product_id' => 'test-product-1',
            'name'       => 'Test Product',
            'price'      => 1000,
            'quantity'   => 2,
        ];

        $response = $this->postJson(route('cart.add'), $payload);

        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => 'success']);
        $response->assertJsonPath('totalItems', 2);

        // Ensure cart facade returns total quantity 2
        $this->assertEquals(2, app('cart')->getTotalQuantity());
    }
}

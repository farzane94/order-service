<?php

namespace Modules\Order\tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Product\Models\Product;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_place_order_successfully()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5, 'price' => 15000]);

        $token = auth()->login($user);

        $response = $this->postJson('/api/orders', [
            'shipping_method' => 'tipax',
            'products' => [
                ['product_id' => $product->id, 'quantity' => 2]
            ]
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total_price' => 15000 * 2 + 30000
        ]);
    }

    public function test_cannot_order_when_stock_is_not_enough()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 1]);

        $token = auth()->login($user);

        $response = $this->postJson('/api/orders', [
            'shipping_method' => 'chapar',
            'products' => [
                ['product_id' => $product->id, 'quantity' => 3]
            ]
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(422);
    }
}


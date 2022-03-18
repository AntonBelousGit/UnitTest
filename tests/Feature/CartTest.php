<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    private function create_user($is_admin = 0)
    {
        $this->user = User::factory()->create(
            [
                'email' => ($is_admin)?'admin@admin.com' : 'user@user.com',
                'password' => bcrypt('password123'),
                'is_admin' => $is_admin,
            ]
        );
    }

    public function  test_button_add_to_cart_is_show()
    {
        $this->create_user();

        Product::factory()->create();

        $response = $this->actingAs($this->user)->get('/products');

        $response->assertSee('Add to cart');
    }
}

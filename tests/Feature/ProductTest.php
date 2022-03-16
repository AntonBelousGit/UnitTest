<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{

    use RefreshDatabase;

    public function test_homepage_contains_empty_products_table()
    {

        $user = User::factory()->create(
            [
                'email' => 'admin@admin.com',
                'password' => bcrypt('password123')
            ]
        );

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('No products found');
    }

    public function test_homepage_contains_non_empty_products_table()
    {
        $product = Product::create([
            'name' => 'Product 1',
            'price' => 99.99
        ]);
        $user = User::factory()->create(
            [
                'email' => 'admin@admin.com',
                'password' => bcrypt('password123')
            ]
        );

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);

        $view_products = $response->viewData('products');

        $this->assertEquals($product->name, $view_products->first()->name);
    }

    public function test_paginated_products_table_doesnt_show_11th_record()
    {
        $product = Product::factory(11)->create();
//        for ($i = 1; $i <=11; $i++)
//        {
//            $product = Product::create([
//                'name' => 'Product ' . $i,
//                'price' => rand(10, 99)
//            ]);
//        }

        $user = User::factory()->create(
            [
                'email' => 'admin@admin.com',
                'password' => bcrypt('password123')
            ]
        );

        $response = $this->actingAs($user)->get('/');

//        $response->assertDontSee($product->name);
        $response->assertDontSee($product->last()->name);
    }
}

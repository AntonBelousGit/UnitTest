<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
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

    public function test_homepage_contains_empty_products_table()
    {
        $this->create_user();
        $response = $this->actingAs($this->user)->get('/products');
        $response->assertStatus(200);
        $response->assertSee('No products found');
    }

    public function test_homepage_contains_non_empty_products_table()
    {
        $this->create_user();
        $product = Product::create([
            'name' => 'Product 1',
            'price' => 99.99
        ]);

        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);

        $view_products = $response->viewData('products');

        $this->assertEquals($product->name, $view_products->first()->name);
    }

    public function test_paginated_products_table_doesnt_show_11th_record()
    {
        $this->create_user();
        $product = Product::factory(11)->create();

        $response = $this->actingAs($this->user)->get('/products');

        $response->assertDontSee($product->last()->name);
    }

    public function test_admin_can_see_product_create_button()
    {
        $this->create_user(1);
        $response = $this->actingAs($this->user)->get('products');
        $response->assertStatus(200);
        $response->assertSee('Add new product');
    }

    public function test_non_admin_can_see_product_create_button()
    {
        $this->create_user();
        $response = $this->actingAs($this->user)->get('products');
        $response->assertStatus(200);
        $response->assertDontSee('Add new product');
    }

    public function test_admin_can_access_products_create_page()
    {
        $this->create_user(1);
        $response = $this->actingAs($this->user)->get('products/create');
        $response->assertStatus(200);
    }
    public function test_non_admin_user_cannot_access_products_create_page()
    {
        $this->create_user();;
        $response = $this->actingAs($this->user)->get('products/create');
        $response->assertStatus(403);
    }

    public function test_store_product_exists_in_database()
    {
        $this->create_user(1);
        $response = $this->actingAs($this->user)->post('products',['name'=>'New Product', 'price'=>99.99]);
        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products',['name'=>'New Product', 'price'=>99.99]);

        $product = Product::orderBy('id','desc')->first();
        $this->assertEquals('New Product', $product->name);
        $this->assertEquals(99.99, $product->price);
    }

    public function test_edit_product_form_contains_correct_name_end_price()
    {
        $this->create_user(1);
        $product = Product::factory()->create();
        $response = $this->actingAs($this->user)->get('/products/'.$product->id.'/edit');

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee($product->price);
    }

    public function  test_update_product_correct_validation_error()
    {
        $this->create_user(1);
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user)->put('/products/'.$product->id, ['name'=>'Test','price'=>99.99]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
    }

    public function  test_update_product_json_correct_validation_error()
    {
        $this->create_user(1);
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user)->
        put('/products/'.$product->id,
            ['name'=>'Test','price'=>99.99],
            ['Accept'=>'Application/json']
        );
        $response->assertStatus(422);
    }

    public function test_delete_product_no_longer_exists_in_database()
    {
        $this->create_user(1);
        $product = Product::factory()->create();

        $this->assertEquals(1,Product::count());

        $response = $this->actingAs($this->user)->delete('/products/'.$product->id);

        $response->assertStatus(302);
        $this->assertEquals(0,Product::count());
    }

    public function test_create_product_file_uploaded()
    {
        $this->create_user(1);
        Storage::fake('local');

        $this->actingAs($this->user)->post('products',
            [
                'name'=> 'Product with photo',
                'price' => 99.99,
                'photo' => UploadedFile::fake()->image('logo.jpg')
            ]
        );

        Storage::disk('local')->assertExists('logos/logo.jpg');
    }
}

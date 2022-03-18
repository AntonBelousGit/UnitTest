<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    //APP_ENV=testing
    //php artisan config:cache --env=testing


    public function test_user_can_view_a_login_form()
    {
        $response = $this->get('/login');

        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    public function test_login_redirects_successfully()
    {
        //Create a user
        User::factory()->create(
            [
                'email' => 'admin@admin.com',
                'password' => bcrypt('password123')
            ]
        );

        $this->post('/login', ['email' => 'admin@admin.com', 'password' => 'password123'])
            ->assertRedirect('/home')
            ->assertSessionHasNoErrors()
            ->assertStatus(302);
    }

    public function test_authenticated_user_can_access_products_table()
    {
        $user = User::factory()->create(
            [
                'email' => 'admin@admin.com',
                'password' => bcrypt('password123')
            ]
        );

        $response = $this->actingAs($user)->get('/products');

        $response->assertStatus(200);

    }

    public function test_unauthenticated_user_cannot_access_products_table()
    {
        $response = $this->get('/products');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        //Assert status not 200
    }

}

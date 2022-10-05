<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_authenticate_using_the_api()
    {
        $user = User::factory()->create();

        $response = $this->post('api/v1/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response
        ->assertOk()
        ->assertJsonStructure(['data' => ['accessToken']]);
    }

    public function test_users_can_not_authenticate_to_api_with_invalid_password()
    {
        $user = User::factory()->create();

        $response = $this->withHeader('accept', 'application/json')
        ->post('api/v1/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422);
    }
}

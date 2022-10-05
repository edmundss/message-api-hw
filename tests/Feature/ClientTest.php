<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientTest extends TestCase
{
    public function test_users_can_get_client_list()
    {
        $user = User::factory()->create();

        $response = $this
        ->actingAs($user, 'sanctum')
        ->get('/api/v1/client');

        $response->assertOk();
    }

    public function test_client_list_can_be_paginated()
    {
        $user = User::factory()->create();
        $client = Client::factory()->count(45)->create();


        $response = $this
        ->actingAs($user, 'sanctum')
        ->get('/api/v1/client?page=2');

        $response->assertOk();
        $this->assertEquals(2, $response->json()['meta']['current_page']);
    }

    public function test_guests_can_not_see_client_list()
    {
        $response = $this
        ->withHeaders(['Accept' => 'application/json'])
        ->get('/api/v1/client');

        $response->assertUnauthorized();
    }

    public function test_guests_can_create_a_new_client()
    {
        $input = [
            "firstName" => "Annette",
            "lastName" => "McKenzie",
            "phoneNumber" => "+37163405309",
            "email" => "megane.kiehn@miller.info"
        ];

        $response = $this
        ->post('/api/v1/client', $input);

        $response
        ->assertCreated()
        ->assertJsonStructure(['data' => [
            'id',
            'firstName',
            'lastName',
            'phoneNumber',
            'email'
        ]]);
    }

    public function test_store_client_request_is_validated()
    {
        $input = [
            "firstName" => "R2D2",
            "lastName" => "C3PO",
            "email" => "@my_instagram_account",
            "phoneNumber" => "00(371)26555555"
        ];

        $response = $this
        ->withHeaders(['Accept' => 'application/json'])
        ->post('/api/v1/client', $input);

        $response
        ->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors' => [
                'firstName',
                'lastName',
                'phoneNumber',
                'email'
            ]
        ]);

    }

    public function test_guests_can_view_client()
    {
        $client = Client::factory()->create();

        $response = $this
        ->get("/api/v1/client/{$client->id}");

        $response
        ->assertOk()
        ->assertJsonStructure(['data' => [
            'id',
            'firstName',
            'lastName',
            'phoneNumber',
            'email'
        ]]);
    }

    public function test_guests_can_update_client_using_patch_method()
    {
        $client = Client::factory()->create();
        $input = [
            "firstName" => "Annette",
            "lastName" => "McKenzie",
        ];

        $response = $this
        ->patch('/api/v1/client/' . $client->id, $input);

        $response
        ->assertOk();
    }

    public function test_guests_can_update_client_using_put_method()
    {
        $client = Client::factory()->create();
        $input = [
            "firstName" => "Annette",
            "lastName" => "McKenzie",
            "phoneNumber" => "+37163405309",
            "email" => "megane.kiehn@miller.info"
        ];

        $response = $this
        ->put('/api/v1/client/' . $client->id, $input);

        $response
        ->assertOk();
    }

    public function test_update_client_request_is_validated()
    {
        $client = Client::factory()->create();
        $input = [
            "firstName" => "R2D2",
            "lastName" => "C3PO",
            "email" => "@my_instagram_account",
            "phoneNumber" => "00(371)26555555"
        ];

        $response = $this
        ->withHeaders(['Accept' => 'application/json'])
        ->put('/api/v1/client/' . $client->id, $input);

        $response
        ->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors' => [
                'firstName',
                'lastName',
                'phoneNumber',
                'email'
            ]
        ]);
    }

    public function test_guest_can_delete_client()
    {
        $client = Client::factory()->create();

        $response = $this
        ->withHeaders(['Accept' => 'application/json'])
        ->delete('/api/v1/client/' . $client->id);

        $response
        ->assertOk();

        $response = $this
        ->withHeaders(['Accept' => 'application/json'])
        ->get('/api/v1/client/' . $client->id)
        ->assertNotFound();
    }

    public function test_user_can_get_list_of_clients_notifications()
    {
        $user = User::factory()->create();

        $client = Client::factory()
        ->hasNotifications(5)
        ->create();

        $response = $this
        ->actingAs($user, 'sanctum')
        ->withHeaders(['Accept' => 'application/json'])
        ->get('/api/v1/client/' . $client->id . '/notifications');

        $response
        ->assertOk();
    }

    public function test_guest_can_not_get_list_of_clients_notifications()
    {

        $client = Client::factory()
        ->hasNotifications(5)
        ->create();

        $response = $this
        ->withHeaders(['Accept' => 'application/json'])
        ->get('/api/v1/client/' . $client->id . '/notifications');

        $response
        ->assertUnauthorized();
    }
}

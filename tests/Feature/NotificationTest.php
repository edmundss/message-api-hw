<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class NotificationTest extends TestCase
{
    /**
     * 9 user can get notification
     * 10 guest can not get notification
    */

    public function test_user_can_get_paginated_and_filtered_list_of_notifications()
    {
        $user = User::factory()->create();

        $client = Client::factory()
        ->hasNotifications(50)
        ->create();

        $response = $this
        ->actingAs($user, 'sanctum')
        ->withHeaders(['Accept' => 'application/json'])
        ->get('/api/v1/notification?page=2&clientId=' . $client->id);

        $json = $response->json();

        $response->assertOk();
        $this->assertEquals($client->id->toString(), $json['data'][0]['clientId']);
        $this->assertEquals(2, $json['meta']['current_page']);
    }

    public function test_guest_can_not_get_list_of_notifications()
    {
        $client = Client::factory()
        ->hasNotifications(5)
        ->create();

        $response = $this
        ->withHeaders(['Accept' => 'application/json'])
        ->get('/api/v1/notification');

        $response->assertUnauthorized();
    }

    public function test_user_can_store_notification()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['create']
        );
        $client = Client::factory()->create();

        $input = [
            "clientId" => $client->id->toString(),
            "channel" => "sms",
            "message" => "Nihil iusto distinctio et omnis sunt blanditiis aut. Dolorem veritatis ut nihil.",
        ];

        $response = $this
        ->withHeaders(['Accept' => 'application/json'])
        ->post('/api/v1/notification', $input);

        $response->assertCreated()
        ->assertJsonStructure(['data' => [
            'id',
            'clientId',
            'channel',
            'message',
            'status'
        ]]);
    }

    public function test_guest_can_not_store_notification()
    {

        $client = Client::factory()->create();

        $input = [
            "clientId" => $client->id->toString(),
            "channel" => "sms",
            "message" => "Nihil iusto distinctio et omnis sunt blanditiis aut. Dolorem veritatis ut nihil.",
        ];

        $response = $this
        ->withHeaders(['Accept' => 'application/json'])
        ->post('/api/v1/notification', $input);

        $response->assertUnauthorized();
    }

    public function test_store_notification_request_is_validated()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['create']
        );

        $input = [
            "clientId" => Str::uuid(),
            "channel" => "pager",
            "message" => "Nihil iusto distinctio et omnis sunt blanditiis aut. Dolorem veritatis ut nihil.",
        ];

        $response = $this
        ->withHeaders(['Accept' => 'application/json'])
        ->post('/api/v1/notification', $input);

        $response->assertStatus(422);
    }

    public function test_user_can_store_multiple_notification()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['create']
        );
        $client = Client::factory()->create();

        $input = [
            [
                "clientId" => $client->id->toString(),
                "channel" => "sms",
                "message" => "Nihil iusto distinctio et omnis sunt blanditiis aut. Dolorem veritatis ut nihil.",
            ],
            [
                "clientId" => $client->id->toString(),
                "channel" => "sms",
                "message" => "Nihil iusto distinctio et omnis sunt blanditiis aut. Dolorem veritatis ut nihil.",
            ],
            [
                "clientId" => $client->id->toString(),
                "channel" => "sms",
                "message" => "Nihil iusto distinctio et omnis sunt blanditiis aut. Dolorem veritatis ut nihil.",
            ]
        ];

        $response = $this
        ->withHeaders(['Accept' => 'application/json'])
        ->post('/api/v1/notification/bulk', $input);

        $response->assertOk();
    }

    public function test_guest_can_not_store_multiple_notification()
    {
        $client = Client::factory()->create();

        $input = [
            [
                "clientId" => $client->id->toString(),
                "channel" => "sms",
                "message" => "Nihil iusto distinctio et omnis sunt blanditiis aut. Dolorem veritatis ut nihil.",
            ],
            [
                "clientId" => $client->id->toString(),
                "channel" => "sms",
                "message" => "Nihil iusto distinctio et omnis sunt blanditiis aut. Dolorem veritatis ut nihil.",
            ],
            [
                "clientId" => $client->id->toString(),
                "channel" => "sms",
                "message" => "Nihil iusto distinctio et omnis sunt blanditiis aut. Dolorem veritatis ut nihil.",
            ]
        ];

        $response = $this
        ->withHeaders(['Accept' => 'application/json'])
        ->post('/api/v1/notification/bulk', $input);

        $response->assertUnauthorized();
    }

    public function test_bulk_store_notification_request_is_validated()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['create']
        );
        $input = [
            [
                "channel" => "sms",
                "message" => "Nihil iusto distinctio et omnis sunt blanditiis aut. Dolorem veritatis ut nihil.",
            ],
            [
                "clientId" => Str::uuid(),
                "channel" => "sms",
                "message" => "Nihil iusto distinctio et omnis sunt blanditiis aut. Dolorem veritatis ut nihil.Nihil iusto distinctio et omnis sunt blanditiis aut. Dolorem veritatis ut nihil.Nihil iusto distinctio et omnis sunt blanditiis aut. Dolorem veritatis ut nihil.",
            ],
            [
                "clientId" => "Hello there!",
                "channel" => "email",
                "message" => "Nihil iusto distinctio et omnis sunt blanditiis aut. Dolorem veritatis ut nihil.",
            ]
        ];

        $response = $this
        ->withHeaders(['Accept' => 'application/json'])
        ->post('/api/v1/notification/bulk', $input);

        $response->assertStatus(422);
    }

    public function test_user_can_view_notification()
    {
        $user = User::factory()->create();
        $notification = Notification::factory()->create();

        $response = $this
        ->actingAs($user, 'sanctum')
        ->withHeaders(['Accept' => 'application/json'])
        ->get('/api/v1/notification/' . $notification->id);

        $response->assertOk()
        ->assertJsonStructure(['data' => [
            'id',
            'clientId',
            'channel',
            'message',
            'status'
        ]]);
    }

    public function test_guest_can_not_view_notification()
    {
        $notification = Notification::factory()->create();

        $response = $this
        ->withHeaders(['Accept' => 'application/json'])
        ->get('/api/v1/notification/' . $notification->id);

        $response->assertUnauthorized();
    }
}

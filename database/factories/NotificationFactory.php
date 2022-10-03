<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $channel = $this->faker->randomElement(['email', 'sms']);
        $message = $this->faker->text($channel == 'sms' ? 140 : 200);

        return [
            'client_id' => Client::factory(),
            'channel' => $channel,
            'message' => $message,
            'status' => $this->faker->randomElement(['pending', 'processing', 'sent', 'failed'])
        ];
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::factory()
        ->count(15)
        ->hasNotifications(13)
        ->create();

        Client::factory()
        ->count(50)
        ->hasNotifications(47)
        ->create();

        Client::factory()
        ->count(47)
        ->hasNotifications(6)
        ->create();

        Client::factory()
        ->count(22)
        ->create();
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Notification;
use App\Mail\NotificationEmail;

class MailTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $notification = Notification::where('channel','email')->first();

        Mail::to($notification->client)
        ->send(new NotificationEmail($notification->message));

        return Command::SUCCESS;
    }
}

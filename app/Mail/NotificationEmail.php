<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $message_content;

    /**
     * Create a new text instance.
     *
     * @return void
     */
    public function __construct($message_content)
    {
        $this->message_content = $message_content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.notification')->with(['message_content' => $this->message_content]);
    }
}

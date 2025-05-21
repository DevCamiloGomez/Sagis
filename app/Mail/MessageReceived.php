<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "Datos de Ingreso SAGIS";
    public $person;

    public $userParams;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($person, $userParams)
    {
        $this->person = $person;
        $this->userParams = $userParams;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->mailer(config('mail.default'))
                    ->view('emails.message-received');
    }
}

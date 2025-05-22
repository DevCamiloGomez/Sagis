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
    public $customMessage;
    public $showCredentials;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($person, $userParams, $customMessage = null, $showCredentials = true)
    {
        $this->person = $person;
        $this->userParams = $userParams;
        $this->customMessage = $customMessage;
        $this->showCredentials = $showCredentials;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->mailer(config('mail.default'))
                    ->view('emails.message-received')
                    ->with([
                        'customMessage' => $this->customMessage,
                        'showCredentials' => $this->showCredentials
                    ]);
    }
}

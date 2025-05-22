<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MassEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject;
    public $content;
    public $person;
    public $includeCredentials;
    public $userParams;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $content, $person, $includeCredentials = false, $userParams = null)
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->person = $person;
        $this->includeCredentials = $includeCredentials;
        $this->userParams = $userParams;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.mass-email');
    }
} 
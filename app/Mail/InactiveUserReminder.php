<?php

namespace App\Mail;

use App\Models\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InactiveUserReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Person $person) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Te echamos de menos! Regresa al portal SAGIS 🎓',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.inactive-user',
        );
    }

    // Compatibilidad con versiones anteriores de Laravel
    public function build(): static
    {
        return $this->subject('¡Te echamos de menos! Regresa al portal SAGIS 🎓')
                    ->view('emails.inactive-user');
    }
}

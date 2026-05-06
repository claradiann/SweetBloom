<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class WelcomeMail extends Mailable
{
    public function __construct(
        public string $name,
        public string $loginUrl
    ) {}

    public function envelope(): Envelope {
        return new Envelope(subject: '🎉 Selamat Datang di SweetBloom!');
    }

    public function content(): Content {
        return new Content(view: 'emails.welcome');
    }
}
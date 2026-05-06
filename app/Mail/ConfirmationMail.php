<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ConfirmationMail extends Mailable
{
    public function __construct(
        public string $name,
        public string $confirmUrl
    ) {}

    public function envelope(): Envelope {
        return new Envelope(subject: '🌸 Konfirmasi Akun SweetBloom Kamu');
    }

    public function content(): Content {
        return new Content(view: 'emails.confirmation');
    }
}
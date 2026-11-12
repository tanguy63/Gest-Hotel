<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CodeVerificationInscription extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $nomComplet,
        public string $code
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vérification de votre adresse e-mail - GEST-HOSTEL',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.code-verification-inscription',
        );
    }
}

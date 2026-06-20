<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DosenWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $recipientEmail;
    public string $temporaryPassword;

    /**
     * Create a new message instance.
     */
    public function __construct(string $recipientEmail, string $temporaryPassword)
    {
        $this->recipientEmail = $recipientEmail;
        $this->temporaryPassword = $temporaryPassword;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat Datang — Akun Dosen Sistem Instalasi Lab',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.dosen_welcome',
            with: [
                'recipientEmail'    => $this->recipientEmail,
                'temporaryPassword' => $this->temporaryPassword,
                'loginUrl'          => url('/login'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}

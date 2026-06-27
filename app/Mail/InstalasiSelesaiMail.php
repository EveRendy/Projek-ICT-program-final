<?php

namespace App\Mail;

use App\Models\Pengajuan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InstalasiSelesaiMail extends Mailable
{
    use Queueable, SerializesModels;

    public Pengajuan $pengajuan;

    /**
     * Create a new message instance.
     */
    public function __construct(Pengajuan $pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pemberitahuan: Instalasi Software Selesai',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.instalasi_selesai',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        if ($this->pengajuan->foto_bukti && file_exists(storage_path('app/public/' . $this->pengajuan->foto_bukti))) {
            $attachments[] = Attachment::fromPath(storage_path('app/public/' . $this->pengajuan->foto_bukti))
                ->as('Bukti_Instalasi_' . ($this->pengajuan->software->nama_software ?? 'Software') . '.jpg')
                ->withMime('image/jpeg');
        }

        return $attachments;
    }
}

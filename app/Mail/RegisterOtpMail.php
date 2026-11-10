<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otp,
        public string $name,
        public string $email
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address', 'halo@homestaygo.desa.id'),
                config('mail.from.name', 'HomeStay Go - Desa Wisata')
            ),
            subject: "{$this->otp} adalah Kode Verifikasi Pendaftaran Akun HomeStay Go Anda",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.register-otp',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

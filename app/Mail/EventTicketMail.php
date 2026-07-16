<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;

class EventTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    // Variabel publik ini otomatis bisa langsung dibaca di file view blade email
    public $transaction;

    /**
     * Membuat instansi pesan baru.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Mengatur amplop email (seperti subjek email).
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-Ticket Resmi Anda: ' . $this->transaction->event->title,
        );
    }

    /**
     * Mengatur konten utama email (mengarahkan ke file blade).
     */
    public function content(): Content
    {
        return new Content(
            // Menentukan lokasi template HTML email di folder resources/views/emails/ticket.blade.php
            view: 'emails.ticket',
        );
    }
}
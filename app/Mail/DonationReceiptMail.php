<?php

namespace App\Mail;

use App\Models\Donation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationReceiptMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Donation $donation) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Donation Receipt - Paroisse Saint-Joseph Artisan',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.donation-receipt',
        );
    }

    public function attachments(): array
    {
        return [
            Pdf::loadView('pdfs.donation-receipt', ['donation' => $this->donation])
                ->filename('donation-receipt-DON-'.strtoupper(substr($this->donation->id, 0, 8)).'.pdf'),
        ];
    }
}

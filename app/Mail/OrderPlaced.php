<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment; // PENTING
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf; // PENTING

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    // 1. Terima Data Order saat class dipanggil
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    // 2. Judul Email
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice Pesanan #' . $this->order->code . ' - Titik Hening',
        );
    }

    // 3. Isi Body Email (Kita buat view simple nanti)
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_placed', // Kita akan buat file ini sebentar lagi
        );
    }

    // 4. Lampirkan PDF Invoice
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => Pdf::loadView('pdf.invoice', ['order' => $this->order])->output(), 'Invoice-' . $this->order->code . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
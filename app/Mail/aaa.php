<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class DailyOrderRecap extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Collection $orders,
        public string $date,
        public float $totalRevenue,
        public int $totalOrders,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📊 Rekap Pesanan Harian ' . $this->date . ' – UP Cireng',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.daily-recap-admin',
        );
    }
}
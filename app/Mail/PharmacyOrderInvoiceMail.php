<?php

namespace App\Mail;

use App\Models\PharmacyOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PharmacyOrderInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public PharmacyOrder $order;
    public string $eventType; // 'verified' | 'delivered'

    public function __construct(PharmacyOrder $order, string $eventType = 'verified')
    {
        $this->order     = $order;
        $this->eventType = $eventType;
    }

    public function envelope(): Envelope
    {
        $subject = match($this->eventType) {
            'verified'  => 'Order #' . $this->order->order_number . ' — Verified & Invoice',
            'delivered' => 'Order #' . $this->order->order_number . ' — Delivered Receipt',
            default     => 'Order #' . $this->order->order_number . ' — Invoice',
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pharmacy.order-invoice',
            with: [
                'order'     => $this->order,
                'eventType' => $this->eventType,
            ]
        );
    }
}

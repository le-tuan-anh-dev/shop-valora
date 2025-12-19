<?php

namespace App\Mail;

use App\Models\Admin\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address'),
                config('mail.from.name')
            ),
            subject: 'Thông báo: Đơn hàng #' . $this->order->order_number . ' đã bị hủy'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-cancelled',
            with: [
                'order' => $this->order,
                'customerName' => $this->order->customer_name,
                'orderNumber' => $this->order->order_number,
                'cancelReason' => $this->order->admin_note ?? "",
                'totalAmount' => number_format($this->order->total_amount, 0, ',', '.'),
                'cancelledAt' => $this->order->cancelled_at->format('d/m/Y H:i'),
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [];
    }
}
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirm extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $fullname, $time, $address, $phone_number, $payment_method, $cart_content, $cart_total;
    public function __construct($fullname, $time, $address, $phone_number, $payment_method, $cart_content, $cart_total)
    {
        $this->fullname = $fullname;
        $this->time = $time;
        $this->address = $address;
        $this->phone_number = $phone_number;
        $this->payment_method = $payment_method;
        $this->cart_content = $cart_content;
        $this->cart_total = $cart_total;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Chi tiết đơn hàng',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'pages.mail.order_confirm',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        return $this->from('badmintop_shop@example.com', 'Badminton Shop')
            ->subject('Gửi mail từ Badminton Shop để xem chi tiết đơn hàng')
            ->view('mail.orderconfirm')
            ->with([
                'fullname' => $this->fullname,
                'time' => $this->time,
                'address' => $this->address,
                'phone_number' => $this->phone_number,
                'payment_method' => $this->payment_method,
                'cart_content' => $this->cart_content,
                'cart_total' => $this->cart_total
            ]);
    }
}

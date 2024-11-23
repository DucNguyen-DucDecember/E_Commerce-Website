<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Buynow extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $fullname, $time, $address, $phone_number, $payment_method, $product_price, $product_name, $product_thumb;
    public function __construct($fullname, $time, $address, $phone_number, $payment_method, $product_name, $product_price, $product_thumb)
    {
        $this->fullname = $fullname;
        $this->time = $time;
        $this->address = $address;
        $this->phone_number = $phone_number;
        $this->payment_method = $payment_method;
        $this->product_price = $product_price;
        $this->product_name = $product_name;
        $this->product_thumb = $product_thumb;
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
            view: 'pages.mail.buynow',
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
            // ->subject('Gửi mail từ Badminton Shop để xem chi tiết đơn hàng')
            // ->view('mail.orderconfirm')
            ->with([
                'fullname' => $this->fullname,
                'time' => $this->time,
                'address' => $this->address,
                'phone_number' => $this->phone_number,
                'payment_method' => $this->payment_method,
                'product_price' => $this->product_price,
                'product_name' => $this->product_name,
                'product_thumb' => $this->product_thumb
            ]);
    }
}

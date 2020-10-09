<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $cart_info; 
    public function __construct($cart_info)
    {
        $this->cart_info = $cart_info;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.register')->from('thocuong97@gmail.com')
                                            ->subject('Xác nhận mua hàng thành công')
                                            ->with($this->cart_info);
    }
}

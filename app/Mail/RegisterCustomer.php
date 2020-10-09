<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterCustomer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $link_active;
    public function __construct($link_active)
    {
        $this->link_active = $link_active;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.customer')->from('thocuong97@gmail.com')
                                            ->subject('Xác nhận đăng ký tài khoản Smart')
                                            ->with($this->link_active);
    }
}

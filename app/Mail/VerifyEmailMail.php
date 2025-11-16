<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $verifyUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(string $verifyUrl)
    {
        $this->verifyUrl = $verifyUrl;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Xác nhận tài khoản của bạn')
                    ->view('emails.verify_email');
    }
}
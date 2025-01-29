<?php

namespace Modules\Auth\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyOrder extends Mailable
{
    use SerializesModels;

    public $otp;

    // Constructor to pass the verification URL to the Mailable
    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Verify Your Order')
                    ->view('emails.verify-order'); // Your custom email view
    }
}

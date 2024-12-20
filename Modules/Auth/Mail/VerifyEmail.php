<?php

namespace Modules\Auth\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use SerializesModels;

    public $verificationUrl;

    // Constructor to pass the verification URL to the Mailable
    public function __construct($verificationUrl)
    {
        $this->verificationUrl = $verificationUrl;
    }

    public function build()
    {
        return $this->subject('Verify Your Email Address')
                    ->view('emails.verify-email'); // Your custom email view
    }
}

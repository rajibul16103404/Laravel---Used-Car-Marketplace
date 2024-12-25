<?php

namespace Modules\Auth\Mail;

use Illuminate\Mail\Mailable;

class ResetPasswordMail extends Mailable
{
    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Password Reset Request')
            ->view('emails.reset_password')->with(['otp' => $this->otp]);
    }
}

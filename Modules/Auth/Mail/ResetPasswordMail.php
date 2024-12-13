<?php

namespace Modules\Auth\Mail;

use Illuminate\Mail\Mailable;

class ResetPasswordMail extends Mailable
{
    public $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function build()
    {
        return $this->subject('Password Reset Request')
            ->view('emails.reset_password')->with(['url' => $this->url]);
    }
}

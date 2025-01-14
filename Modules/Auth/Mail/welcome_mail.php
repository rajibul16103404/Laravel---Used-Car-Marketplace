<?php

namespace Modules\Auth\Mail;

use Illuminate\Mail\Mailable;

class Welcome_mail extends Mailable
{
    public $password;

    public function __construct($password)
    {
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('This is the welcome email')
            ->view('emails.welcome_mail')->with(['password' => $this->password]);
    }
}

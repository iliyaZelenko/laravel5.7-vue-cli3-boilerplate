<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $resetUrlWithToken;

    public $subject = 'Сброс пароля';
    /**
     * Create a new message instance.
     *
     * @param $resetUrlWithToken
     *
     */
    public function __construct($resetUrlWithToken)
    {
        $this->resetUrlWithToken = $resetUrlWithToken;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.auth.forgotPassword');
    }
}

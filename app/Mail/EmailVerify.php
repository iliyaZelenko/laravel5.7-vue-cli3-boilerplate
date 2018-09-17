<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailVerify extends Mailable
{
    use Queueable, SerializesModels;

    public $emailVerifyUrl;

    public $subject = 'Подтверждение почты';
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($emailVerifyUrl, $afterSignup = true)
    {
        $this->emailVerifyUrl = $emailVerifyUrl;
        $this->afterSignup = $afterSignup;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown($this->afterSignup ? 'emails.auth.emailVerify' : 'emails.profile.emailVerify');
    }
}

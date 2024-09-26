<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user,$user_password,$user_email,$supportEmail, $cc2,$bcc2;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($option,$cc,$bcc)
    {
        $this->user_password = $option['user_password'];
        $this->user_email = $option['user_email'];
        $this->supportEmail = $option['supportEmail'];
        $this->user = $option['user'];
        $this->cc2 = $cc;
        $this->bcc2 = $bcc;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Your ICC trade desk registration is successfully';
        $mail = $this->from(config('app.setting')['company_email'], config('app.setting')['company_name'])
                    ->subject($subject)
                    ->markdown('emails.userRegistration');
        if ($this->cc2 != null) {
            $mail->cc($this->cc2);
        }
        if ($this->bcc2 != null) {
            $mail->bcc($this->bcc2);
        }
        return $mail;
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SupportRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    // public $user,$type,$name,$status,$assigned_user, $cc2,$bcc2;
    public $maildata;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($maildata)
    {
        $this->maildata = $maildata;
        // $this->type = $option['type'];
        // $this->name = $option['name'];
        // $this->status = $option['status'];
        // $this->assigned_user = $option['assigned_user'];
        // $this->user = $option['user'];
        // $this->cc2 = $cc;
        // $this->bcc2 = $bcc;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $subject = 'Support Request '.$this->type;
        // $mail = $this->from(config('app.setting')['company_email'], config('app.setting')['company_name'])
        //             ->subject($subject)
        //             ->markdown('emails.supportRequest');
        // if ($this->cc2 != null) {
        //     $mail->cc($this->cc2);
        // }
        // if ($this->bcc2 != null) {
        //     $mail->bcc($this->bcc2);
        // }
        return $this->view('mail.supportRequest');
    }
}

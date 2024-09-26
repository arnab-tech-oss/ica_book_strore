<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FeedbackUrlMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data,$ticket,$cc2,$user_name,$base_url,$url,$ticket_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$ticket,$cc = null)
    {
        $this->data = $data;
        $this->user_name = $this->data['user_name'];
        $this->base_url = $this->data['base_url'];
        $this->url = $this->data['url'];
        $this->ticket_name = $this->data['ticket_name'];
        $this->ticket = $ticket;
        $this->cc2 = $cc;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $cc = null;
        $bcc = null;
        $setting = config('app.setting');
        if (!empty($setting)) {
            $cc = $this->cc2;
            $account_email = explode(",", $setting['account_email']);
            $cc = array_merge($cc, $account_email);
            $bcc = explode(',', $setting['email_bcc']);
        }
        $subject = 'Give the feedback for support request ' . $this->ticket->title;
        $mail = $this->from(config('app.setting')['company_email'], config('app.setting')['company_name'])
                    ->subject($subject)
                    ->markdown('emails.feedback_url_mail');
        if ($cc != null) {
            $mail->cc($cc);
        }
        if ($bcc != null) {
            $mail->bcc($bcc);
        }
        return $mail;
    }
}

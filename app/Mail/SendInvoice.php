<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $bills,$files,$ticket,$invoices;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($bills,$files)
    {
        $this->bills = $bills;
        $this->files = $files;
        $this->ticket = $bills->ticket->toArray();
        $this->invoices = $bills->invoices->toArray();
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
            $cc = explode(',', $setting['email_cc']);
            $account_email = explode(",", $setting['account_email']);
            $cc = array_merge($cc, $account_email);
            $bcc = explode(',', $setting['email_bcc']);
        }
        $mail = $this->from(config('app.setting')['company_email'], config('app.setting')['company_name'])
                    ->subject("Your Support Request Bill")
                    ->markdown('emails.sendInvoice')
                    ->attach($this->files);
        if ($cc != null) {
            $mail->cc($cc);
        }
        if ($bcc != null) {
            $mail->bcc($bcc);
        }
        return $mail;
    }
}

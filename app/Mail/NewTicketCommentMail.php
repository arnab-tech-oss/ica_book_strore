<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
class NewTicketCommentMail extends Mailable
{
    use Queueable, SerializesModels;

    // public $ticket_name,$comment,$ticket_url,$cc2,$bcc2,$ticket;
    public $maildata;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($maildata)
    {
        $this->maildata = $maildata;
        // $this->comment = $comment;
        // $this->ticket_url = $ticket_url;
        // $this->cc2 = $cc;
        // $this->bcc2 = $bcc;
        // $this->ticket = $ticket;
    }

    /**
     * Build the message.
     *
     *  @return \Illuminate\Mail\Mailables\Envelope
     */
    // public function envelope()
    // {
    //     return new Envelope(
    //         subject: "Welcome to I&CA Book Store Registration Confirmation",
    //     );
    // }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    // public function content()
    // {
    //     return new Content(
    //         view: 'emails.newTicketComment',
    //     );
    // }

      /**
     * Get the attachments for the message.
     *
     * @return array
     */
    // public function attachments()
    // {
    //     return [];
    // }
    public function build()
    {
        $mail = $this->from(config('app.setting')['company_email'], config('app.setting')['company_name'])
            ->subject('New comment added on ' . $this->maildata->app_name)
                    ->markdown('emails.newTicketComment');
        // if ($this->cc2 != null) {
        //     $mail->cc($this->cc2);
        // }
        // if ($this->bcc2 != null) {
        //     $mail->bcc($this->bcc2);
        // }
        return $mail;
    }
}

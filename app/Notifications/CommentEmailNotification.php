<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class CommentEmailNotification extends Notification
{
    use Queueable;

    public $cc,$bcc,$user_id;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($comment,$user_id,$assigned_to_user)
    {
        $this->comment = $comment;
        $this->user_id = $user_id;
        $setting = config('app.setting');
        if($setting){
            $cc = array_merge(explode(',',$setting['email_cc']),explode(',',$assigned_to_user->email));
            $this->cc = $cc;
            $this->bcc = $setting['email_bcc'];
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New comment on ticket '.$this->comment->ticket->title)
                    ->greeting('Hi,')
                    ->cc($this->cc)
                    ->bcc($this->bcc)
                    ->line('New comment on ticket '.$this->comment->ticket->title.':')
                    ->line('')
                    ->line(Str::limit($this->comment->comment_text, 500))
                    ->action('View full ticket', route(optional($notifiable)->id ? 'admin.tickets.show' : 'tickets.show', $this->comment->ticket->id))
                    ->line('Thank you')
                    ->line(config('app.name') . ' Team')
                    ->salutation(' ');
    }
}

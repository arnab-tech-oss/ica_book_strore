<?php

namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmailCustomLogger
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $message = $event->message;
        DB::table('email_logs')->insert([
            'user_id' => Auth::user()->id ?? null,
            'date' => Carbon::now()->format('Y-m-d H:i:s'),
            'from' => $this->formatAddressField($message, 'From'),
            'to' => $this->formatAddressField($message, 'To'),
            'cc' => $this->formatAddressField($message, 'Cc'),
            'bcc' => $this->formatAddressField($message, 'Bcc'),
            'subject' => $message->getSubject(),
            'body' => (string) $message->getBody(),
            'headers' => $message->getHeaders()->toString(),
            'attachments' => $this->saveAttachments($message),
        ]);
    }

    /**
     * Format address strings for sender, to, cc, bcc.
     *
     * @param \Swift_Message $message
     * @param string $field
     * @return null|string
     */
    function formatAddressField(\Swift_Message $message, string $field): ?string
    {
        $headers = $message->getHeaders();
        return $headers->get($field)?->getFieldBody();
    }

    /**
     * Collect all attachments and format them as strings.
     *
     * @param \Swift_Message $message
     * @return string|null
     */
    protected function saveAttachments(\Swift_Message $message): ?string
    {
        if (empty($message->getChildren())) {
            return null;
        }

        return (collect($message->getChildren()))
            ->map(fn($part) => $part->toString())
            ->implode("\n\n");
    }
}

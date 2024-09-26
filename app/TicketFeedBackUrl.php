<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketFeedBackUrl extends Model
{
    use HasFactory;

    protected $table = 'ticket_feedback_url';

    protected $fillable = [
        'status',
        'ticket_id',
        'user_id',
        'url',
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketsTags extends Model
{
    use HasFactory;


    protected $table = 'tickets_tags';
    protected $fillable = [
        'tag_id',
        'ticket_id'
    ];
}

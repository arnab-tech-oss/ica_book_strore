<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketServices extends Model
{
    use HasFactory;

    protected $table = "service_ticket";
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillTicketServices extends Model
{
    use HasFactory;

    protected $table = 'bill_ticket_services';

    public function ticket(){
        return $this->hasOne(Ticket::class,'id','ticket_id');
    }

    public function services(){
        return $this->hasOne(Service::class,'id','service_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $table = "bill";

    protected $fillable = [
        'ticket_id',
        'bill_cost',
        'remaining_cost',
        'created_by',
        'invoice_id',
        'ref_no'

    ];

    const FULLY_PAID = 'fully_paid';
    const PARTIALLY_PAID = 'partially_paid';
    const NOT_PAID = 'not_paid';

    public function billServices(){
        return $this->hasMany(BillTicketServices::class,'bill_id','id');
    }

    public function invoices(){
        return $this->hasOne(Invoices::class,'id','invoice_id');
    }

    public function payments(){
        return $this->hasMany(Payments::class,'bill_id','id');
    }

    public function ticket(){
        return $this->hasOne(Ticket::class,'id','ticket_id');
    }

    public function user(){
        return $this->hasOne(User::class,'id','created_by');
    }

    public function links(){
        return $this->hasMany(Link::class,'bill_id','id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $table= 'links';

    protected $fillable = [
        'id',
        'ticket_id',
        'payment_url',
        'created_at',
        'updated_at'
    ];


    public function tickets(){
        return $this->hasOne(Ticket::class,'id','ticket_id');
    }
}

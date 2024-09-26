<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

     protected $table = "payments";

     public function bill(){
         return $this->hasOne(Bill::class,'id','bill_id');
     }
}

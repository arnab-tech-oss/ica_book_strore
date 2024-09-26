<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Service extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Auditable;

    protected $table = 'services';

    protected $appends = ['attachments'];

    protected $fillable = [
        'name',
        'description',
        'service_type',
        'category_id',
        'cost',
        'contact_info',
        'status',
        'currency',
        'member_cost',
        'assigned_user',
        'parent_service_id',
        'reg_no',
        'banner'
    ];

    public function category(){
        return $this->hasOne(Category::class,'id','category_id');
    }

    public function parent(){
        return $this->hasOne(Service::class,'id','parent_service_id');
    }

    public function childrens(){
        return $this->hasMany(Service::class,'parent_service_id','id');
    }

    public function getAttachmentsAttribute()
    {
        return $this->getMedia('attachments');
    }

    public function serviceTickets(){
        return $this->hasMany(TicketServices::class,'ticket_id','id');
    }
}

<?php

namespace App;

use App\Mail\NewTicketCommentMail;
use App\Scopes\AgentScope;
use App\Traits\Auditable;
use App\Notifications\CommentEmailNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use WpOrg\Requests\Auth;
use App\TicketFeedback;

class Ticket extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable;

    public $table = 'tickets';

    protected $appends = [
        'attachments'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'content',
        'status_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'priority_id',
        'author_name',
        'author_email',
        'assigned_to_user_id',
        'created_by',
        'reg_no'

    ];

    public function ticketServices()
    {
        return $this->belongsToMany(Service::class);
    }

    public static function boot()
    {
        parent::boot();

//        Ticket::observe(new \App\Observers\TicketActionObserver);

        static::addGlobalScope(new AgentScope);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'ticket_id', 'id');
    }

    public function service()
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function getAttachmentsAttribute()
    {
        return $this->getMedia('attachments');
    }

    public function getDocumentsAttribute()
    {
        return $this->getMedia('documents');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class, 'priority_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assigned_to_user()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id','id');
    }

    public function scopeFilterTickets($query)
    {
        $query->when(request()->input('service'), function($query) {
                $query->whereHas('ticketServices', function($query) {
                    $query->whereId(request()->input('service'));
                });
            })
            ->when(request()->input('status'), function($query) {
                $query->whereHas('status', function($query) {
                    $query->whereId(request()->input('status'));
                });
            });
    }

    public function sendCommentNotification($comment,$ticket)
    {
        $users = \Auth::user();
        $user_id = null;
        $assigned_user_id = null;
        if($users->isUser()){
            $user_id = $ticket->assigned_to_user_id;
            $assigned_user_id = $users->email;
        }else{
            $user_id = $ticket->created_by;
            $assigned_user_id = $ticket->assigned_to_user->email;
        }
        if($user_id){
            $users = User::where('id',$user_id)->first();
        }
        $cc = $bcc = [];
        $setting = config('app.setting');
        if($setting){
            $cc = explode(',',$setting['email_cc']);
            $bcc = explode(',',$setting['email_bcc']);
        }
        if($assigned_user_id){
            $cc = array_merge($cc,explode(',',$assigned_user_id));
        }
        if(empty($users)){
            $users = \Auth::user();
        }
        $comments = Str::limit($comment->comment_text, 500);
        $ticket_name = $comment->ticket->title;
        $ticket_url = 'https://tradedesk.indianchamber.org/admin/tickets/'.$ticket->id;
        try {
            Mail::to($users->email)->send(new NewTicketCommentMail($ticket_name,$comments,$ticket_url,$cc,$bcc,$ticket));
        }catch(\Exception $e){
            Log::info($e->getMessage());
        }
    }

    public function ticketTags(){
        return $this->hasMany(TicketsTags::class,'ticket_id','id');
    }

    public function bills(){
        return $this->hasMany(Bill::class,'ticket_id','id');
    }

    public function ticketFeedbacks(){
        return $this->hasMany(TicketFeedback::class,'ticket_id','id');
    }
}

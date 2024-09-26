<?php

namespace App;

use App\Traits\Auditable;
use Carbon\Carbon;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use SoftDeletes, Notifiable, HasApiTokens,Auditable;

    public $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
        'email_verified_at',
    ];

    protected $fillable = [
        'title',
        'name',
        'email',
        'number',
        'country_id',
        'status',
        'member_id',
        'city',
        'company',
        'currency',
        'designation',
        'is_gst',
        'gst_no',
        'billing_address',
        'password',
        'decrypt_password',
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token',
        'email_verified_at',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to_user_id', 'id');
    }

    public function userTickets()
    {
        return $this->hasMany(Ticket::class, 'created_by', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function isAdmin()
    {
        return $this->roles->contains(1);
    }

    public function isAdminOrDeptAdmin()
    {
        $deptAdmin = config('constant.department_admin_role_id');
        return $this->roles->contains(1) || $this->roles->contains($deptAdmin);
    }

    public function isDeptUser()
    {
        $deptUser = config('constant.department_user_role_id');
        return $this->roles->contains($deptUser);
    }

    public function isDeptAdmin()
    {
        $deptAdmin = config('constant.department_admin_role_id');
        return $this->roles->contains($deptAdmin);
    }

    public function isUser()
    {
        $userRole = config('constant.user_role_id');
        return $this->roles->contains($userRole);
    }

    public function country()
    {
        return $this->hasOne(Countries::class,'id','country_id');
    }
}

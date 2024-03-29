<?php

namespace App\Models;

use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
class Account extends Authenticatable implements MustVerifyEmail
{
    use HasFactory,HasApiTokens,Notifiable;

    protected $fillable=['password','user_id','email'];
    protected $hidden = ['password','remember_token'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setEmailAttribute($value)
    {
        try
        {
            $user = User::find($this->attributes['user_id']);
            $user->email = $value;
            $user->save();
        }catch(Exception $e)
        {
        }


        $this->attributes['email'] = $value;
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Interfaces\WalletFloat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Traits\HasWalletFloat;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable implements Wallet,WalletFloat,MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable,HasWalletFloat;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'user_name',
        'pincode',
        'SSN'
    ];
    protected $guard ='api-users';
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
    public function setPincodeAttribute($value)
    {
        $this->attributes['pincode'] = bcrypt($value);
    }

    public function card()
    {
        return $this->hasOne(Card::class,'user_id','id');
    }

    public function setSNNAttribute($value)
    {
        $this->attributes['SNN'] = Crypt::encryptString($value);
    }
    public function getSNNAttribute($value)
    {
        return  Crypt::decryptString($value);
    }
    public function family()
    {
        return $this->hasMany(FamilyMember::class,'sponsor_id','id')
        ->select('user_name','name','sponsor_id');
    }
    public function getGuardNameForApiToken()
    {
        return $this->guard_name;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function account()
    {
        return $this->hasOne(Account::class,'user_id','id');
    }

    public function notifications()
    {
        return
        $this->hasMany(UserNotification::class,'user_id','id')
        ->select('message','type');
    }
    public function transaction_details()
    {
        return $this
        ->hasMany(TransactionDetail::class,'user_id','id')
        ->with(['transaction' => function($query){
            $query->select('id','amount','type');
        },'subcategory' => function($query){
            $query->select('name','category_id','id');
        }]);
    }
}

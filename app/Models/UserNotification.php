<?php

namespace App\Models;

use Bavix\Wallet\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class UserNotification extends Model
{
    use HasFactory;
    protected $table = 'user_notifications';
    protected $fillable = ['message','type','user_id','transaction_id'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function setMessageAttribute($value)
    {
        $this->attributes['message'] = Crypt::encryptString($value);
    }

    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = Crypt::encryptString($value);
    }

    public function getTypeAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function getMessageAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class,'transaction_id','id');
    }
}

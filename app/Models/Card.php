<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Card extends Model
{
    use HasFactory;

    protected $fillable=['card_no','cvv','user_id','expiration_date','type'];
    protected $hidden=['created_at','updated_at'];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }


    public function getCvvAttribute($value)
    {
        return Crypt::decryptString($value);
    }


    public function getCardNoAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function getTypeAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function getExpirationDateAttribute($value)
    {
        return Crypt::decryptString($value);
    }
}

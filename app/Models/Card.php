<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable=['card_no','cvv','user_id','expiration_date','type'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}

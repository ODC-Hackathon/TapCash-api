<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinCodeReset extends Model
{
    use HasFactory;
    protected $fillable =['user_name','token'];
    protected $hidden = ['created_at','updated_at'];


    public function setTokenAttribute($value)
    {
        $this->attributes['token'] = bcrypt($value);
    }






}

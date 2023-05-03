<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
class FamilyMember extends Authenticatable
{
    use HasApiTokens,HasFactory;
    protected $guard ='api-family';
    protected $fillable=['name','sponsor_id','user_name','phone_number','password','total_amount','percentage'];
    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
    public function getGuardNameForApiToken()
    {
        return $this->guard_name;
    }
}

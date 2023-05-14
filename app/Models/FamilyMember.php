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
    protected $fillable=
    [
        'name','sponsor_id'
        ,'user_name','phone_number'
        // ,'total_amount'
        // ,'percentage'
        ,'family_id',
        'pincode',
        'amount_added',
        'allowed_money',
        'spent_money'
    ];
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];


    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
    public function getGuardNameForApiToken()
    {
        return $this->guard_name;
    }

    public function sponser()
    {
        return $this->belongsTo(User::class,'sponsor_id','id');
    }

    public function setPincodeAttribute($value)
    {
        $this->attributes['pincode'] = bcrypt($value);
    }

    public function transaction_details()
    {
        return $this
        ->hasMany(TransactionDetail::class,'familymember_id','id')
        ->select('id','transaction_id','type','subcategory_id','familymember_id')
        ->with(['transaction' => function($query){
            $query->select('id','amount','type');
        },'subcategory' => function($query){
            $query->select('name','category_id','id');
        }]);
    }

    public function notifications()
    {
        return
        $this->hasMany(UserNotification::class,'family_id','id')
        ->select('message','type','id');
    }

    public function permissions()
    {
        return $this->hasOne(MemberPermission::class,'member_id','id')
        ->select('permissions','member_id');
    }
    
}

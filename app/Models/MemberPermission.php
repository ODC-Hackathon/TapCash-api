<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberPermission extends Model
{
    use HasFactory;

    protected $fillable =['member_id','permissions'];


    public function setPermissionsAttribute($value)
    {
        $this->attributes['permissions'] = json_encode($value);
    }

    public function getPermissionsAttribute($value)
    {
        return json_decode($value);
    }

    public function member()
    {
        return $this->belongsTo(FamilyMember::class,'member_id','id');
    }
}

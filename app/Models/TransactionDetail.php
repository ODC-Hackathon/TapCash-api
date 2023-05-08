<?php

namespace App\Models;

use Bavix\Wallet\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;
    protected $fillable=['type','transaction_id','subcategory_id','user_id','familymember_id'];
    protected $hidden = ['created_at','updated_at'];



    public function subcategory()
    {
        return $this
        ->belongsTo(SubCategory::class,'subcategory_id','id')
        ->with('category')
        ->select('id','name','category_id');
    }
    public function transaction()
    {
        return
        $this
        ->belongsTo(Transaction::class,'transaction_id','id')
        ->select('id','type','amount','confirmed');
    }

    public function user()
    {
        return
        $this
        ->belongsTo(User::class,'user_id','id');
    }

    public function familymeember()
    {
        return
        $this
        ->belongsTo(FamilyMember::class,'familymember_id','id');
    }
}

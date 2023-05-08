<?php

namespace App\Models;

use Bavix\Wallet\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;
    protected $fillable=['type','transaction_id'];
    protected $hidden = ['created_at','updated_at'];




    public function transaction()
    {
        return
        $this
        ->belongsTo(Transaction::class,'transaction_id','id')
        ->select('id','type','amount','confirmed');
    }
}

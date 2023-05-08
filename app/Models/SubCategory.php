<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','category_id'];

    public function transactions()
    {
        return $this->hasMany(TransactionDetail::class,'subcategory_id','id');
    }


    public function category()
    {
        return $this->belongsTo(Category::class)
        ->select('name','id');
    }
}

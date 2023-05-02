<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable=['name'];
    public static function booted()
    {
        # code...

        static::addGlobalScope(fn($query)=> $query->orderby('name'));
    }


    public function sub_categries()
    {
        # code...

        $this->hasMany(Sub_Category::class,'category_id','id');
    }
}

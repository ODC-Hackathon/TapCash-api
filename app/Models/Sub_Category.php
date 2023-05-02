<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sub_Category extends Model
{
    use HasFactory;
    protected $fillable = ['name','category_id'];




    public function category()
    {
        # code...

        return $this->belongsTo(Category::class,'category_id','id')
            ->orderby('name');
    }
}

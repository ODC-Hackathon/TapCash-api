<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryContoller extends BaseController
{
    //

    public function getCategories()
    {
        $categories = Category::orderby('name')->get();


        return $this->succes($categories);
    }
}

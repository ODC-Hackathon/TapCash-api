<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Sub_Category;
use Illuminate\Http\Request;

class Sub_CategoryController extends BaseController
{
    //
    public function get_sub_categories()
    {
        # code...
        $sub_categories = Sub_Category::all();


        return $this->succes($sub_categories);
    }
}

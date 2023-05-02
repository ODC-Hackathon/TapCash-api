<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    //

    public function succes($data) :JsonResponse
    {
        return response()->json([
            'data'=> $data,
        ],200);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    //


    public function success($data , $message = 'success') :JsonResponse
    {

        return response()->json([
            'data' => $data,
            'message' =>$message
        ],200);
    }

    public function error($message,$status=404)
    {


        return response()->json([
            'message' =>$message
        ],$status);
    }
}

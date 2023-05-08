<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class NotificationController extends BaseController
{
    //
    public function index(Request $request)
    {
        return $request->user()->notifications;
    }
}

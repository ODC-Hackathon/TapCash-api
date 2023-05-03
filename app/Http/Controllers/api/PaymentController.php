<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\Payment_Method_Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PaymentController extends Controller
{
    //


    public function get_payment_types()
    {
        $payments = Cache::rememberForever('payment_method_types', fn()=> Payment_Method_Type::all());

        return response([
            'data' =>$payments,
        ],200);
    }



    public function SendMoney(PaymentRequest $request)
    {
        # code...
        
    }
}

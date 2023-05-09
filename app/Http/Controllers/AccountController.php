<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends BaseController
{
    //

    public function profiles(AccountRequest $request)
    {
        $request->authenticate();

        $account = Account::where('email',$request->email)
        ->select('user_id','email')
        ->with(['user' => function($query){
            $query->select('id','name','user_name');
        },'user.family'])
        ->get();

        Auth::guard('accounts')->user()?->tokens()?->delete();
        
        $token = Auth::guard('accounts')
        ->user()
        ->createToken('Laravel Password Grant Client',['accounts'])->plainTextToken;

        return response()->json([
                    'data'=>[
                        'account' => $account,
                        'token' => $token
                    ],
                ],200);
    }
}

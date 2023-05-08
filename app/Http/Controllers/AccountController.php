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
        $request->authenticate();

        $account = Account::where('email',$request->email)
        ->select('user_id','email')
        ->with(['user' => function($query){
            $query->select('id','name','user_name');
        },'user.family'])
        ->get();

        $token = Auth::guard('accounts')
        ->user()
        ->createToken('Laravel Password Grant Client',['accounts'])->plainTextToken;

        return response()->json([
                    'data'=>$account,
                    'token' => $token,
                ],200);

        // $credentials = $request->only('email','password');
        // if(Auth::guard('accounts')->attempt($credentials,$request->boolean('remember')))
        // {


        //     $token = Auth::guard('accounts')->user()->createToken('Laravel Password Grant Client',['accounts'])->plainTextToken;
        //     // Auth::guard('accounts')->logout();

        //     return response()->json([
        //         'data'=>$account,
        //         'token' => $token,
        //     ],200);
        //     // return $this->success($account);
        // }else
        // {
        //     return $this->error('please Verify your provided data');
        // }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends BaseController
{
    //

    public function profiles(Request $request)
    {
        $request->validate(['email'=>['required','exists:accounts,email','email'],'password'=>['required']]);
        $credentials = $request->only('email','password');
        if(Auth::guard('accounts')->attempt($credentials,$request->boolean('remember')))
        {
            $data = Account::where('email',$request->email)
            ->select('user_id','email')
            ->with(['user' => function($query){
                $query->select('id','name','user_name');
            },'user.family'])
            ->get();

            // Auth::guard('accounts')->logout();
            
            return $this->success($data);
        }else
        {
            return $this->error('please Verify your provided data');
        }
    }
}

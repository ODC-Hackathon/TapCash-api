<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Http\Requests\api\user\ResetPincodeRequest;
use App\Mail\api\ResetUserPinCodeEmail;
use App\Models\Account;
use App\Models\PinCodeReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

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



    public function sentResetEmail(Request $request)
    {
        $request->validate([
            'user_name'=>['required','exists:users,user_name']
        ]);

        $account = $request->user();
        $resetData = PinCodeReset::where('user_name',$request->user_name)->first();

        if(!empty($resetData) != 0)
        {
            $resetData->delete();
        }

        $token = Password::createToken($request->user());
        $reset = PinCodeReset::create([
            'user_name'=> $request->user_name,
            'token' =>$token,
        ]);

        Mail::to($account->email)->send(new ResetUserPinCodeEmail($token,$request->user_name));

        return $this->success(['message'=>'Email has been Sent Check Your Email']);
    }


    public function show_reset_form(Request $request , $token)
    {

        $pincodessent = PinCodeReset::all();
        $user_name = null;
        foreach($pincodessent as $sent_data)
        {
            if(Hash::check($token,$sent_data->token))
            {
                $user_name = $sent_data->user_name;
                break;
            }
        }

        return view('pincode.resetpincode',compact('user_name','token'));
    }


    public function resetPincode(ResetPincodeRequest $request)
    {

        $user = User::where('user_name',$request->user_name)->first();
        $account = $user->account;

        if(!Hash::check($request->password,$account->password))
            return redirect()->back()->with('unauthanticated',"You Can't reset Pincode Incorrect Password");


        $pincodeToken = PinCodeReset::where('user_name',$user->user_name)->first();
        if(!Hash::check($request->token,$pincodeToken->token))
            return redirect()->back()->with('unauthanticated',"Invalid Link");


        $user->pincode = $request->pincode;
        $user->save();

        $pincodeToken->delete();
        return redirect()->back()->with('success',"pincode Updated Successfully");
    }

}

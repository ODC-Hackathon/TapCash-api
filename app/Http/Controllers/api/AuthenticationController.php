<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\auth\LoginRequest;
use App\Http\Requests\api\auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Account;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends BaseController
{
    public function register(RegisterRequest $request) {

        $response = null;
        $status=null;
        DB::beginTransaction();
        try {

            $user = User::create([
                'email' => $request->email,
                'phone_number'=>$request->phone_number,
                'user_name'=>$request->user_name,
                'password'=>$request->password,
                'name'=>$request->name,
                'SSN' => $request->SSN,
                'pincode'=>$request->pincode
            ]);
            $account = Account::create([
                'email'=> $request->email,
                'password'=>$request->password,
                'user_id' => $user->id,
            ]);
            
            Auth::guard('api-users')->login($user);
            $token = $user->createToken($request->phone_number,['api-users'])->plainTextToken;
            $response =
                [
                    'token' => $token,
                    'user'=>User::where('id',$user->id)
                    ->select('id','user_name','email','phone_number','name')
                    ->get()
                ];
            event(new Registered($account));
            $status =200;

        }catch (Exception $e){
            $response = ['message'=>$e->getMessage()];
            $status=400;
            DB::rollback();
        }
        DB::commit();
        return response()->json([
            'data'=>$response,
        ], $status);
    }
    public function login (LoginRequest $request) {
        $request->authenticate();
        $response=null;
        if(strcmp($request->type ,'user') ===0)
        {
            $response = $this->create_token('api-users');
        }elseif(strcmp($request->type ,'family') ===0){
            $response = $this->create_token('api-family');
        }

        Auth::guard('accounts')->user()?->tokens()?->delete();
        return $this->success($response);
    }
    public function create_token($guard)
    {
        $user = Auth::guard($guard)
        ->user();
        $user->tokens()?->delete();

        return  array([
            'user'=>
                    $user
                    ->where('id',$user->id)
                    ->select('user_name','phone_number','name','id')
                    ->get(),
            'token'=>
                    $user->createToken('Laravel Password Grant Client',[$guard])
                    ->plainTextToken
        ]);
    }
    public function get_users(Request $request)
    {

        $request->validate(['email'=>['required','exists:users,email','email']]);
        $sponser = User::where('email',$request->email)
        ->select('id','user_name')
        ->with('family')
        ->get();

        return New UserResource($sponser);
    }

    public function logout (Request $request)
    {
        $request->user()->tokens()->delete();
        Auth::guard('api-family')->logout();

        return $this->success([
            'message'=>'You have been successfully logged out!'
        ]);
    }
}

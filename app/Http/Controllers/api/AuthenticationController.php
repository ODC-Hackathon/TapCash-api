<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\auth\LoginRequest;
use App\Http\Requests\api\auth\RegisterRequest;
use App\Models\User;
use App\Rules\PhonNumberRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function register(RegisterRequest $request) {
        $user = User::create([
            'email' => $request->email,
            'phone_number'=>$request->phone_number,
            'user_name'=>$request->user_name,
            'password'=>$request->password,
            'name'=>$request->name,
        ]);
        $token = $user->createToken('Laravel Password Grant Client')->plainTextToken;
        $response = ['token' => $token];
        return response($response, 200);
    }
    public function login (LoginRequest $request) {

        // $request->authenticate();
        $credentials = $request->only('user_name', 'password');
        if(Auth::guard('api-users')->attempt(['user_name'=>$request->user_name,'password'=>$request->password]) )
        {
            $user = Auth::guard('api-users')->user();
            $token = $user->createToken('Laravel Password Grant Client',['api-users'])->plainTextToken;
            return response([
                'data'=>$user,
                'token'=>  $token,
            ],200);
        }
        elseif(Auth::guard('api-family')->attempt(['user_name'=>$request->user_name,'password'=>$request->password]))
        {
            // $user = $request->user('api-family');
            $user = Auth::guard('api-family')->user();
            $token = $user->createToken('Laravel Password Grant Client',['api-family'])->plainTextToken;
            return response([
                'data'=>$user,
                'token'=>  $token,
            ],200);
        }
        return response(['username' => __('auth.failed')],400);

        // $user->tokens()->delete();


    }

    public function logout (Request $request) {
        $request->user()->tokens()->delete();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }
}

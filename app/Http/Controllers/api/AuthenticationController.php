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

        $request->authenticate();
        $user = Auth::user();
        $user->tokens()->delete();

        $token = Auth::user()->createToken('Laravel Password Grant Client')->plainTextToken;
        return response([
            'data'=>$user,
            'token'=>  $token,
        ],200);
    }

    public function logout (Request $request) {
        $request->user()->tokens()->delete();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }
}

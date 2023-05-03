<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\auth\LoginRequest;
use App\Http\Requests\api\auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Rules\PhonNumberRule;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
        Auth::guard('api-users')->login($user);
        $token = $user->createToken($request->phone_number,['api-users'])->plainTextToken;
        $response =
            [
                'token' => $token,
                'data'=>$user
            ];
        return response($response, 200);
    }
    public function login (LoginRequest $request) {

        $request->authenticate();

        $response=null;
        if($request->type == 'user')
        {
            $response=$this->create_token('api-users');
        }elseif($request->type == 'family'){
            $response=$this->create_token('api-family');
        }
        // $credentials = $request->only('user_name', 'password');
        // if(Auth::guard('api-users')->attempt(['user_name'=>$request->user_name,'password'=>$request->password]))
        // {
        //     $user = Auth::guard('api-users')->user();
        //     $token = $user->createToken('Laravel Password Grant Client',['api-users'])->plainTextToken;
        //     return response([
        //         'data'=>$user,
        //         'token'=>  $token,
        //     ],200);
        // }
        // elseif(Auth::guard('api-family')->attempt(['user_name'=>$request->user_name,'password'=>$request->password]))
        // {
        //     // $user = $request->user('api-family');
        //     $user = Auth::guard('api-family')->user();
        //     $token = $user->createToken('Laravel Password Grant Client',['api-family'])->plainTextToken;
        //     return response([
        //         'data'=>$user,
        //         'token'=>  $token,
        //     ],200);
        // }

        return response($response,200);
        // $user->tokens()->delete();


    }
    public function create_token($guard)
    {
        $user = Auth::guard($guard)->user();
        $user->tokens()?->delete();
        return  array([
            'data'=>$user,
            'token'=>$user->createToken('Laravel Password Grant Client',[$guard])->plainTextToken
        ]);
    }
    public function get_users(Request $request)
    {
        $request->validate(['email'=>['required','exists:users,email','email']]);


        $sponser = User::with('family')->where('email',$request->email)->get();

        return New UserResource($sponser);
    }

    public function logout (Request $request) {
        $request->user()->tokens()->delete();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }
}

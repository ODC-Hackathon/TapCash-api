<?php

namespace App\Http\Middleware;

use App\Models\Account;
use App\Models\FamilyMember;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

class AccountVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request?->user();
        $status = 400;

        if($user InstanceOf Account)
        {
            if(!$user->hasVerifiedEmail())
                return $this->resend_email($request,$user);
                // return response()->json(['errors'=>$message],$status);
        }
        elseif($user InstanceOf User)
        {
            if(!$user->account->hasVerifiedEmail())
                return $this->resend_email($request,$user->account);
                // return response()->json(['errors'=>$message],$status);
        }
        elseif($user InstanceOf FamilyMember)
        {
            if(!$user->sponser->account->hasVerifiedEmail())
                    return $this->resend_email($request,$user->sponser->account);
                // return response()->json(['errors'=>$message],$status);
        }else if($user === null)
        {
            $account = Account::where('email',$request->email)?->first();
            if($account !== null)
            {
                if(!$account->hasVerifiedEmail())
                {
                    return $this->resend_email($request,$account);
                    // $account->sendEmailVerificationNotification();
                    // return response()->json(['errors'=>$message],$status);
                }
                return $next($request);
            }
        }
        return $next($request);
    }

    protected function resend_email(Request $request,$account)
    {
        $status=400;
        $message = [
            'message'=>'please Verify your account email'
        ];
        $response = Route::dispatch($request->create(env('APP_URL').'/api/email/verify/resend','post',[
            'id'=>$account->id
        ]));
        // $response = Http::post(env('APP_URL').'/api/email/verify/resend',[
        //     'id'=>$account->id,
        // ]);

        // $account->sendEmailVerificationNotification();
        return response()->json(['errors'=>$response],$status);
    }
}

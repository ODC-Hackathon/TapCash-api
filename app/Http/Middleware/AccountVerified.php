<?php

namespace App\Http\Middleware;

use App\Models\Account;
use App\Models\FamilyMember;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

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
        $user = $request->user();
        if($user InstanceOf Account)
        {
            if(!$user->hasVerifiedEmail())
                return response()->json(['message'=>'please Verify your account email']);
        }
        elseif($user InstanceOf User)
        {
            if(!$user->account->hasVerifiedEmail())
                return response()->json(['message'=>'please Verify your account email']);
        }
        elseif($user InstanceOf FamilyMember)
        {
            if(!$user->sponser->account->hasVerifiedEmail())
                return response()->json(['message'=>'please Verify your account email']);
        }
        return $next($request);
    }
}

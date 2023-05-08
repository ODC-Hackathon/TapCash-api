<?php

namespace App\Http\Middleware;

use App\Models\FamilyMember;
use App\Models\User;
use Closure;
use Hamcrest\Core\IsInstanceOf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
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

        if($request->user() InstanceOf User)
        {
            return $next($request);
        }
        return response()->json(['message'=>'Unauthorized'],401);
    }
}

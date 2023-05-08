<?php

namespace App\Http\Middleware;

use App\Models\FamilyMember;
use Closure;
use Illuminate\Http\Request;

class FamilyMiddleware
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
        if($request->user() InstanceOf FamilyMember)
        {
            return $next($request);
        }
        return response()->json(['message'=>'Unauthorized'],401);
    }
}

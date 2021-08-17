<?php

namespace App\Http\Middleware;

use Closure;
use Response;
use Illuminate\Support\Facades\Auth;
use App\User;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()['type'] == 'Admin'){
            return $next($request);
        }else{
            return response()->json(['message' => 'Can not verify ' . Auth::user()['username'] .' as admin']);
        }
    }
}

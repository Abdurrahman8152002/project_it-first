<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $token = $request->header('token');


        $user = JWTAuth::setToken($token)->authenticate();


        if ($user->role_id !== 2) {

            return response()->json(['error' => 'Unauthorized'], 403);
        }


        return $next($request);
    }
}

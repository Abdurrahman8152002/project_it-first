<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class Register_middleware
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request->all(), [

            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $email = $request->input('email');
        $password = $request->input('password');

        if (!isset($email) || !isset($password)){
            return response()->json(['msg'=>'All fields are required']);
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            return response()->json(['message' => 'Email already exists'], 400);
        }

        $user = User::create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $token = JWTAuth::fromUser($user);

        $request->request->add(['token' => $token]);

        return $next($request);
    }
}

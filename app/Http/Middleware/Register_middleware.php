<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTFactory;

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


        $role = Role::where('name', 'User')->first();

        if (!$role) {

            return response()->json(['message' => 'User role not found'], 404);
        }


        $user = User::create([
            'email' => $email,
            'password' => Hash::make($password),
            'role_id' => $role->id,
            'status' => 1,
        ]);
        $payload = JWTFactory::sub($user->id)->
            user_id($user->id)->make();
        $token = JWTAuth::fromUser($user,$payload);

        $request->request->add(['token' => $token]);

        return $next($request);
    }

}

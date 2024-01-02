<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;

class login_middleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {


        if ($request->hasHeader('token')) {
            $token = $request->header('token');
            try {
                $decoded_token = JWTAuth::setToken($token)->getPayload();

                $userId = $decoded_token['sub'];

                $user = User::find($userId);

                if ($user && $user->status != -1) {
                    // Update user status
                    $user->status = 1;
                    $user->save();

                    return response()->json([
                        'msg' => 'you are logged in',
                        'data' => [
                            'token' => $token
                        ]
                    ]);
                } else {
                    return response()->json(['msg' => 'Please log in with email and password']);
                }
            } catch (Exception $exception) {
                return response()->json(['msg' => 'An error occurred while processing the token', 'error' => $exception->getMessage()]);
            }
        } else {
            $email = $request->input('email');
            $password = $request->input('password');

            $user = User::where('email', $email)->first();

            if ($user && Hash::check($password, $user->password)) {
                // Update user status
                $user->status = 1;
                $user->save();

                $token = JWTAuth::fromUser($user);

                return response()->json([
                    'msg' => 'you are logged in',
                    'data' => [
                        'token' => $token
                    ]
                ]);
            } else {
                return response()->json(['msg' => 'Invalid email or password']);
            }
        }
    }
}

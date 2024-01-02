<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function register(Request $request){


        return response()->json([
            'msg' => 'you are registered',
            'data' => [
                'email' => $request->get('email'),
                'password' => $request->get('password'),
                'token' => $request->get('token')
            ]
        ]);
    }



    public function login(Request $request){
        return response()->json([
            'msg' => 'you are logged in',
            'data' => [

                'token' => $request->get('token')
            ]
        ]);
    }

    public function logout(Request $request)
    {
        if (!$request->hasHeader('token')) {
            return response()->json(['msg' => 'we dont even know you']);
        }

        $token = $request->header('token');
        try {
            $decoded_token = JWTAuth::setToken($token)->getPayload();

            $userId = $decoded_token['sub'];

            $user = User::find($userId);

            if ($user) {

                $user->status = -1;
                $user->save();

                return response()->json(['msg' => 'Logged out successfully']);
            } else {
                return response()->json(['msg' => 'Invalid token']);
            }
        } catch (Exception $exception) {
            return response()->json(['msg' => 'An error occurred while processing the token', 'error' => $exception->getMessage()]);
        }
    }

}

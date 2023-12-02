<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class User extends Controller
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
               // 'email' => $request->get('email'),
                //'password' => $request->get('password'),
                'token' => $request->get('token')
            ]
        ]);
    }
}

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

    public function logout(Request $request)
    {
        if (!$request->hasHeader('token')) {
            return response()->json(['msg' => 'we dont even know you']);
        }
        $token = $request->header('token');
        $json_data=json_decode(file_get_contents('C:\wamp64\www\project\public\users.json'), true);
        $found = false;
        foreach ($json_data as $key => &$data) {
            if ($data['token'] == $token) {
                $found = true;
                unset($data['token']); // Remove the token from the user object
                break;
            }
        }
        if ($found) {
            file_put_contents('C:\\wamp64\\www\\project\\public\\users.json', json_encode($json_data));
            return response()->json(['msg' => 'Logged out successfully']);
        } else {
            return response()->json(['msg' => 'Invalid token']);
        }


    }
}

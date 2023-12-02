<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class login_middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $json_data = json_decode(file_get_contents(public_path('users.json')), true);
        if (!$request->hasHeader('token')) {
            $email = $request->input(['email']);
            $password = $request->input(['password']);
            $found = false;
            foreach ($json_data as $key => $data) {
                if ($data['email'] == $email && $data['password'] == $password) {
                    $found = true;
                    $token = base64_encode(json_encode(['email' => $email, 'password' => $password]));
                    $json_data[$key]['token'] = $token;
                    break;
                }
            }
            if ($found) {
                file_put_contents('C:\\wamp64\\www\\project\\public\\users.json', json_encode($json_data));
                $request->attributes->add(['token' => $token]);
                return $next($request);
            } else {
                return response()->json(['msg' => 'Invalid email or password']);
            }

        }




        $token = $request->header('token');
        try {
            $base64_token = base64_decode($token);
            $token_string = json_decode($base64_token,true);
            if (!is_array($token_string) || !isset($token_string['email']) || !isset($token_string['password'])) {
                return response()->json(['msg' => 'Token does not contain required fields']);
            }

            $found = false;
            foreach ($json_data as $data) {
                if ($data['token'] == $token) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                return response()->json(['msg' => 'Token does not match any user']);
            }
        } catch (\Exception $exception) {
            return response()->json(['msg' => 'An error occurred while processing the token', 'error' => $exception->getMessage()]);
        }
        $request->attributes->add(['token' => $token]);
        return $next($request);
    }}

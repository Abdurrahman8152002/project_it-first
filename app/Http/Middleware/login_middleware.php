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
        if (!$request->hasHeader('token')) {
            return response()->json(['msg' => 'no token found']);
        }
        $json_data = json_decode(file_get_contents(public_path('users.json')), true);

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

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function PHPUnit\Framework\isEmpty;

class Register_middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $users =json_decode(file_get_contents('C:\wamp64\www\project\public\users.json'), true);

        $email = $request->input('email');
        $password = $request->input('password');
//echo $email;
if (!isset($email) || !isset($password)){
    return response()->json(['msg'=>'All fields are required']);
}
//echo $email;
        foreach ($users as $user) {
            if ($user['email'] == $email) {
                return response()->json(['message' => 'Email already exists'], 400);
            }
        }
        $param=$request->all();
        $token=base64_encode(json_encode($param));

        $users[] = ['email' => $email, 'password' => $password,'token'=>$token];

        file_put_contents('C:\wamp64\www\project\public\users.json', json_encode($users));
$request->request->add(['token'=>$token]);
        return $next($request);
    }
}


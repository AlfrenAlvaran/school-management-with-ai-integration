<?php

namespace Core\Middlewares;

use Core\Http\Request;
use Core\Http\Response;
use Core\Http\Session;

class AuthMiddleware
{
    public function handle(Request $request)
    {
        $publicRoutes  = ['/', '/login', '/register', '/verify-otp'];

        if (in_array($request->path(), $publicRoutes)) {
            return $request;
        }

        if (!Session::has('user_id')) {
            return (new Response())->redirect('/');
        }

        return $request;
    }
}

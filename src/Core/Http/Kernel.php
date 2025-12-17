<?php

namespace Core\Http;

use Core\Middlewares\AuthMiddleware;

class Kernel
{

    protected array $globalMiddleware = [
        AuthMiddleware::class
    ];

    public function handle(Request $request): Response|Request
    {
        foreach ($this->globalMiddleware as $middlewareClass) {
            $middleware = new $middlewareClass;

         
            $response = $middleware->handle($request);

            if ($response instanceof Response) {
                return $response;  
            }
        }

        return $request; 
    }

    public function registerGlobalMiddleware(array $middleware)
    {
        $this->globalMiddleware = $middleware;
    }

    
}





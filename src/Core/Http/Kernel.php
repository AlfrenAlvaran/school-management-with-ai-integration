<?php

namespace Core\Http;

class Kernel
{

    protected array $globalMiddleware = [];

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





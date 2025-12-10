<?php

namespace Core\Router;

class MiddlewarePipeline
{
    protected array $stack = [];
    protected $destination;

    public function __construct(array $stack, callable $destination)
    {
        $this->stack = $stack;
        $this->destination = $destination;
    }
    public function process($request)
    {
        $pipeline = array_reduce(
            array_reverse($this->stack),
            fn($next, $middleware) => fn($req) => (new $middleware)->handle($req, $next),
            $this->destination
        );
        return $pipeline($request);
    }
}

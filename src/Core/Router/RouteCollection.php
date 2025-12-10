<?php

namespace Core\Router;

class RouteCollection
{
    protected array $routes = [];

    public function add(Routes $route): void
    {
        $this->routes[] = $route;
    }

    public function all(): array
    {
        return $this->routes;
    }

    public function find(string $method, string $path): array
    {
        $allowed = [];
        foreach ($this->routes as $route) {
            $allowed[] = $route->method();
            if ($route->method() !== $method) continue;
            $params = $route->matches($path);
            if ($params !== null) return [$route, $params];
        }
        return [null, $allowed];
    }
}

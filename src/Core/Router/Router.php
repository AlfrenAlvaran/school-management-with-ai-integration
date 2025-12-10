<?php
namespace Core\Router;
use Core\Http\Request;
use Core\Http\Response;
use Core\Router\MiddlewarePipeline;
use Core\Router\RouteCollection;
use Core\Router\Routes;

class Router
{
    protected RouteCollection $routes;
    protected array $groupStack = [];

    public function __construct()
    {
        $this->routes = new RouteCollection();
    }

    public function get(string $path, $handler): Routes
    {
        return $this->add('GET', $path, $handler);
    }

    public function post(string $path, $handler): Routes
    {
        return $this->add('POST', $path, $handler);
    }

    public function add(string $method, string $path,  $handler): Routes
    {
        if ($this->groupStack) {
            $prefix = end($this->groupStack)['prefix'] ?? '';
            $middleware = end($this->groupStack)['middleware'] ?? [];
            $path = $prefix . $path;
        } else {
            $middleware = [];
        }

        $route = new Routes($method, $path, $handler);
        if ($middleware) $route->middleware($middleware);

        $this->routes->add($route);
        return $route;
    }

    public function group(array $options, callable $callback)
    {
        $this->groupStack[] = $options;
        $callback($this);
        array_pop($this->groupStack);
    }

    public function dispatch(Request $request)
    {
        $path = rtrim($request->path(), '/') ?: '/';
        [$route, $allowed] = $this->routes->find($request->method(), $path);

        if (!$route) {
            if (in_array($request->method(), $allowed)) {
                return (new Response)->setStatus(405)->text('405 Method Not Allowed')->send();
            }
            return (new Response)->setStatus(404)->text('404 Not Found')->send();
        }

        $request->setParams($route->matches($path));

        // FIXED:
        $handler = $route->handler();
        $middleware = $route->getMiddleware();

        $pipeline = new MiddlewarePipeline($middleware, function ($request) use ($handler) {
            if (is_array($handler)) {
                [$controller, $method] = $handler;

                $controllerInstance = new $controller($request);
                $params=array_values($request->params());
                return $controllerInstance->$method(...$params);
            }

            // callable handler
            return $handler($request);
        });

        return $pipeline->process($request);
    }
}

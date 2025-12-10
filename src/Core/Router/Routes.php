<?php

namespace Core\Router;

class Routes
{
    protected string $method;
    protected string $path;
    protected $handler;
    protected array $middleware = [];
    protected ?string $name = null;
    protected array $paramNames = [];
    protected string $regex;

    public function __construct(string $method, string $path, $handler)
    {
        $this->method = strtoupper($method);
        $this->path = $path;
        $this->handler = $handler;
        $this->compilePattern();
    }

    public function compilePattern()
    {
        preg_match_all('#\{([^}]+)\}#', $this->path, $matches);
        $this->paramNames = $matches[1] ?? [];
        $pattern = preg_replace('#\{([^}]+)\}#', '([^/]+)', $this->path);
        $this->regex = '#^' . rtrim($pattern, '/') . '/?$#';
    }

    public function matches(string $path): ?array
    {
        if (!preg_match($this->regex, $path, $matches)) return null;
        array_shift($matches);
        return array_combine($this->paramNames, $matches);
    }

    public function method(): string
    {
        return $this->method;
    }
    public function handler()
    {
        return $this->handler;
    }

    public function middleware(array $list): self
    {
        $this->middleware = $list;
        return $this;
    }

    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}

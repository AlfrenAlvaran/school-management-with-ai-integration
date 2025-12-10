<?php

namespace Core\Http;

class Request
{
    protected array $query = [];
    protected array $post = [];
    protected array $cookies = [];
    protected array $files = [];
    protected array $server = [];
    protected array $headers = [];
    protected array $params = [];
    protected ?string $rawBody = null;
    protected ?array $json = null;

    public static function capture(): self
    {
        $request = new self();
        $request->query = $_GET ?? [];
        $request->post = $_POST ?? [];
        $request->cookies = $_COOKIE ?? [];
        $request->files = $_FILES ?? [];
        $request->server = $_SERVER ?? [];
        $request->headers = $request->parseHeaders();
        $request->rawBody = file_get_contents('php://input');
        $request->json = json_decode($request->rawBody, true) ?? null;
        return $request;
    }

    public function parseHeaders(): array
    {
        $headers = [];
        foreach ($this->server as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $name = strtolower(str_replace('_', '-', substr($key, 5)));
                $headers[$name] = $value;
            }
        }
        if (isset($this->server['CONTENT_TYPE'])) {
            $headers['content-type'] = $this->server['CONTENT_TYPE'];
        }
        if (isset($this->server['CONTENT_LENGTH'])) {
            $headers['content-length'] = $this->server['CONTENT_LENGTH'];
        }

        return $headers;
    }

    public function method(): string
    {
        $method = $this->server['REQUEST_METHOD'] ?? 'GET';
        if ($method === "POST" && isset($this->post['_method'])) {
            return strtoupper($this->post['_method']);
        }
        return strtoupper($method);
    }


    public function path(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        $position = strpos($uri, '?');
        return $position === false ? $uri : substr($uri, 0, $position);
    }

    public function input(string $key, $default = null)
    {
        if (isset($this->post[$key])) return $this->post[$key];
        if (isset($this->query[$key])) return $this->query[$key];
        if ($this->json && isset($this->json[$key])) return $this->json[$key];
        return $default;
    }

    public function only(array $keys): array
    {
        $data = [];
        foreach ($keys as $key) {
            $value = $this->input($key);
            if ($value !== null) {
                $data[$key] = $value;
            }
        }
        return $data;
    }

    public function all(): array
    {
        $data = array_merge($this->query, $this->post);
        return $this->json ? array_merge($data, $this->json) : $data;
    }


    public function json(): ?array
    {
        return $this->json;
    }

    public function raw(): ?string
    {
        return $this->rawBody;
    }

    public function header(string $key, $default = null)
    {
        $key = strtolower($key);
        return $this->headers[$key] ?? $default;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function cookie(string $key, $default = null)
    {
        return $this->cookies[$key] ?? $default;
    }

    public function cookies(): array
    {
        return $this->cookies;
    }

    public function file(string $key)
    {
        return $this->files[$key] ?? null;
    }
    public function files(): array
    {
        return $this->files;
    }
    public function ip(): ?string
    {
        if (isset($this->server['HTTP_X_FORWARDED_FOR'])) {
            return trim(explode(',', $this->server['HTTP_X_FORWARDED_FOR'])[0]);
        }
        return $this->server['REMOTE_ADDR'] ?? null;
    }

    public function fullUrl(): string
    {
        $scheme = (!empty($this->server['HTTPS']) && $this->server['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $this->server['HTTP_HOST'] ?? 'localhost';
        $uri = $this->server['REQUEST_URI'] ?? '/';
        return "{$scheme}://{$host}{$uri}";
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    public function param(string $key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }

    public function params(): array
    {
        return $this->params ?? [];
    }

    public function post(?string $key, $default = null)
    {
        return $this->post[$key] ?? $default;
    }

    public function get(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->query;
        }

        return $this->query[$key] ?? $default;
    }
}

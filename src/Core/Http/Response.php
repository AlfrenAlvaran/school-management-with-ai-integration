<?php
namespace Core\Http;

class Response
{
    protected int $status = 200;
    protected array $headers = [];
    protected string $content = '';
    protected array $cookies = [];

    public function __construct(string $content = '', int $status = 200, array $headers = [])
    {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function header(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function json($data, int $status = 200): self
    {
        $this->status = $status;
        $this->headers['Content-Type'] = 'application/json';
        $this->content = json_encode($data);
        return $this;
    }

    public function text(string $text, int $status = 200): self
    {
        $this->status = $status;
        $this->headers['Content-Type'] = 'text/plain; charset=utf-8';
        $this->content = $text;
        return $this;
    }

    public function html(string $html, int $status = 200): self
    {
        $this->status = $status;
        $this->headers['Content-Type'] = 'text/html; charset=utf-8';
        $this->content = $html;
        return $this;
    }

    public function cookie(string $name, string $value, int $expires = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httpOnly = true): self
    {
        $this->cookies[] = compact('name', 'value', 'expires', 'path', 'domain', 'secure', 'httpOnly');
        return $this;
    }

    public function redirect(string $url, int $status = 302): self
    {
        $this->status = $status;
        $this->headers['Location'] = $url;
        return $this;
    }

    public function download(string $filePath, ?string $filename = null): self
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            $this->status = 404;
            $this->content = 'File not found.';
            return $this;
        }
        $filename = $filename ?: basename($filePath);

        $this->headers = [
            'Content-Type' => mime_content_type($filePath),
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => filesize($filePath)
        ];

        $this->content = file_get_contents($filePath);
        return $this;
    }

    public function send() {
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        foreach ($this->cookies as $cookie) {
            setcookie(
                $cookie['name'],
                $cookie['value'],
                $cookie['expires'],
                $cookie['path'],
                $cookie['domain'],
                $cookie['secure'],
                $cookie['httpOnly']
            );
        }
        echo $this->content;
    }
}

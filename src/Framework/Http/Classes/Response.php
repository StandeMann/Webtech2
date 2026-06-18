<?php

namespace Framework\Http\Classes;

use Framework\Http\Interfaces\ResponseInterface;

class Response implements ResponseInterface
{
    private string $content;
    private int $statusCode;
    private array $headers;

    public function __construct(
        string $content = '',
        int $statusCode = 200,
        array $headers = []
    ) {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        echo $this->content;
    }

    function getHeaders(): array
    {
        return $this->headers;
    }

    function hasHeader(string $name): bool
    {
        return array_key_exists($name, $this->headers);
    }

    function getHeader(string $name): string
    {
        return $this->headers[$name];
    }

    function withHeader(string $name, string $value): static
    {
        $new = clone $this;
        $new->headers[$name] = $value;
        return $new;
    }

    function withoutHeader(string $name): static
    {
        $new = clone $this;
        unset($new->headers[$name]);
        return $new;
    }

    function getStatusCode(): int
    {
        return $this->statusCode;
    }

    function withStatusCode(int $code): static
    {
        $this->statusCode = $code;
        return $this;
    }

    function getBody(): string
    {
        return $this->content;
    }

    function withBody(string $body): static
    {
        $this->content = $body;
        return $this;
    }
}
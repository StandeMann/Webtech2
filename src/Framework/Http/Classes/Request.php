<?php

namespace Framework\Http\Classes;

use App\Model\User;
use Framework\Http\Interfaces\RequestInterface;

class Request implements RequestInterface{
    private array $server;
    private array $get;
    private array $post;
    private ?User $user = null;

    private array $headers;
    private array $cookies;


    private function __construct(
        array $server,
        array $get,
        array $post,
        array $headers,
        array $cookies
    ) {
        $this->server = $server;
        $this->get = $get;
        $this->post = $post;
        $this->headers = $headers;
        $this->cookies = $cookies;
    }

    public static function FromGlobals(): self
    {
        return new self(
            $_SERVER,
            $_GET,
            $_POST,
            getallheaders(),
            $_COOKIE
        );
    }

    public function withUser(User $user): self
    {
        $clone = clone $this;
        $clone->user = $user;

        return $clone;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'] ?? 'GET';
    }


    public function getParam(string $key): ?string{
        return $this->get[$key] ?? null;
    }

    public function getParams(): array{
        return $this->get;
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
        return $this->headers[$name] ?? '';
    }

    function withHeader(string $name, string $value): static
    {
        $clone = clone $this;
        $clone->headers[$name] = $value;
        return $clone;
    }

    function withoutHeader(string $name): static
    {
        $clone = clone $this;
        unset($clone->headers[$name]);
        return $clone;
    }

    function getUri(): Uri{
        return new Uri(parse_url(
            $this->server['REQUEST_URI'],
            PHP_URL_PATH));
    }

    function getServerParams(): array
    {
        return $this->server;
    }

    function getCookieParams(): array
    {
        return $this->cookies;
    }

    function getQueryParams(): array
    {
        return [];
    }

    function getUploadedFiles(): array
    {
       return [];
    }

    function getParsedBody(): null|array{
        return $this->get;
    }

    function getAttributes(): array
    {
        return $this->post;
    }

    function getAttribute(string $name, mixed $default = null): mixed
    {
        return $this->post[$name] ?? $default;
    }

    function withAttribute(string $name, mixed $value): static
    {
        $clone = clone $this;
        $clone->post[$name] = $value;
        return $clone;
    }

    function withoutAttribute(string $name): static
    {
        $clone = clone $this;
        unset($clone->post[$name]);
        return $clone;
    }
}
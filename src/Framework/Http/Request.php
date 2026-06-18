<?php

namespace Framework\Http;

use App\Model\User;

class Request
{
    private array $server;
    private array $get;
    private array $post;
    private ?User $user = null;


    private function __construct(
        array $server,
        array $get,
        array $post,
    ) {
        $this->server = $server;
        $this->get = $get;
        $this->post = $post;
    }

    public static function FromGlobals(): self
    {
        return new self(
            $_SERVER,
            $_GET,
            $_POST,
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

    public function getPath(): string
    {
        return parse_url(
            $this->server['REQUEST_URI'],
            PHP_URL_PATH
        );
    }

    public function getPost(): array
    {
        return $this->post;
    }

    public function getParam(string $key): ?string{
        return $this->get[$key] ?? null;
    }

    public function getParams(): array{
        return $this->get;
    }


}
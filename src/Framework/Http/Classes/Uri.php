<?php

namespace Framework\Http\Classes;

class Uri
{
    public function __construct(
        private string $uri
    ) {}

    public function __toString(): string
    {
        return $this->uri;
    }
}
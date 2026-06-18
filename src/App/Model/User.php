<?php

namespace App\Model;

class User
{
    public function __construct(
        public int $id,
        public string $username,
        public string $email,
        public string $password,
        public string $role)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRole(): string
    {
        return $this->role;
    }


}
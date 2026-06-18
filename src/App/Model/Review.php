<?php

namespace App\Model;

class Review
{
    public function __construct(
        public int $id,
        public string $description,
        public float $stars,
        public int $book_id,
        public int $user_id,
    ){}
}
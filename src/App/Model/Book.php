<?php

namespace App\Model;

class Book
{
    public function __construct(
        public int $id,
        public string $title,
        public string $author,
        public string $genre,
        public string $description,
        public int $user_id,
        public int $showable,
        public float $average,
        public int $reviewCount
    ){}
}
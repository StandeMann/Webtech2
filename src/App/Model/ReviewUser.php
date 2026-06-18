<?php

namespace App\Model;

class ReviewUser
{
    public string $description;
    public int $stars;
    public string $username;
    public int $userId;
    public int $reviewId;
    public function __construct(
        string $description,
        string $stars,
        string $username,
        int $userId,
        int $reviewId)
    {
        $this->description = $description;
        $this->stars = $stars;
        $this->username = $username;
        $this->reviewId = $reviewId;
        $this->userId = $userId;
    }
}
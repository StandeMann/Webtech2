<?php

namespace App\Repository;
use App\Model\ReviewUser;
use Framework\Database\Interfaces\ConnectionInterface;
use PDO;

class ReviewFunctions
{
    private ConnectionInterface $connection;
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function addReview(string $description, int $stars, int $book_id, int $user_id): void{
        $this->connection->execute(
            "INSERT INTO reviews (description, stars, book_id, user_id)VALUES (:description, :stars, :book_id, :user_id)",
            $description, $stars, $book_id, $user_id);
    }

    public function getReviews(int $book_id): array{
        $stmt = $this->connection->getPdo()->prepare("SELECT r.id AS review_id, r.description, r.stars, r.user_id, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE book_id = :book_id");
        $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $reviews = [];
        foreach($rows as $row){
            $reviews[] = new ReviewUser(
                $row['description'],
                $row['stars'],
                $row['username'],
                $row['user_id'],
                $row['review_id']
            );
        }
        return $reviews;
    }

    public function deleteReview(int $review_id): void{
        $this->connection->execute("DELETE FROM reviews WHERE id = :review_id", $review_id);
    }
}
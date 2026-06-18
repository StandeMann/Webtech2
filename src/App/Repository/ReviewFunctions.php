<?php

namespace App\Repository;
use App\Model\ReviewUser;
use PDO;
class ReviewFunctions
{
    private PDO $pdo;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function addReview(string $description, int $stars, int $book_id, int $user_id): void
    {
        $query = $this->pdo->prepare(
            "INSERT INTO reviews (description, stars, book_id, user_id)VALUES (:description, :stars, :book_id, :user_id)");

        $query->execute([$description, $stars, $book_id, $user_id]);
    }

    public function getReviews(int $book_id): array{
        $stmt = $this->pdo->prepare("SELECT r.id AS review_id, r.description, r.stars, r.user_id, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE book_id = :book_id");
        $stmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        var_dump($rows);
//        exit;
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
        $query = $this->pdo->prepare("DELETE FROM reviews WHERE id = :review_id");
        $query->execute([$review_id]);
    }
}
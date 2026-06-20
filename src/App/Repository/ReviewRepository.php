<?php

namespace App\Repository;

use App\Mapper\ReviewMapper;
use App\Model\Review;
use App\Model\ReviewUser;
use Framework\Database\Classes\Connection;
use Framework\Database\Classes\IdentityMap;
use Framework\Database\Interfaces\ConnectionInterface;
use Framework\Database\Interfaces\DataMapperInterface;
use Framework\Database\Interfaces\IdentityMapInterface;
use Framework\Database\Interfaces\RepositoryInterface;
use PDO;

class ReviewRepository implements RepositoryInterface
{
    private DataMapperInterface $mapper;
    private IdentityMapInterface $identityMap;
    private Connection $connection;
    public function __construct(ConnectionInterface $connection){
        $this->mapper = new ReviewMapper($connection);
        $this->identityMap = new IdentityMap();
        $this->connection = $connection;
    }

    public function get(int $id): object
    {
        if ($this->identityMap->has($id)) {
            return $this->identityMap->get($id);
        }

        $object = $this->mapper->get($id);
        if (!$object) {
            throw new \Exception(strval($id));
        }

        $this->identityMap->add($object->rowid, $object);
        return $object;
    }

    function save(object $object): void{
        if (!$object) {
            throw new \Exception('Not found');
        }

        $this->identityMap->add($object->id, $object);
    }

    function remove($object): void
    {
        if (!$object) {
            throw new \Exception('Not found');
        }
        $this->identityMap->remove($object);
    }

    public function addReview(string $description, int $stars, int $book_id, int $user_id): void{
        $this->mapper->insert(new Review(0, $description, $stars, $book_id, $user_id));
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

    private function getReview(int $id): Review{
        $row = $this->mapper->select("SELECT * FROM reviews WHERE id = :id", $id);
        return new Review(
            $row[0]['id'],
            $row[0]['description'],
            $row[0]['stars'],
            $row[0]['book_id'],
            $row[0]['user_id']
        );
    }

    public function deleteReview(int $review_id): void{
        $review = $this->getReview($review_id);
        $this->mapper->delete($review);
    }

}
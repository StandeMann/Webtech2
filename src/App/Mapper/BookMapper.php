<?php

namespace App\Mapper;

use App\Model\Book;
use Framework\Database\Interfaces\ConnectionInterface;
use Framework\Database\Interfaces\DataMapperInterface;

class BookMapper implements DataMapperInterface{
    private ConnectionInterface $connection;

    public function __construct(ConnectionInterface $connection){
        $this->connection = $connection;
    }

    function get(int $id): object
    {
        $sql = 'SELECT * FROM books WHERE id = :id';
        $params = $id;
        $row = $this->connection->query($sql, $params);
        if($row){
            $avgStars = $this->getAverageStars($row[0]['id']);
            $numReviews = $this->numberOfReviews($row[0]['id']);
            return new Book(
                $row[0]['id'],
                $row[0]['title'],
                $row[0]['author'],
                $row[0]['genre'],
                $row[0]['description'],
                $row[0]['user_id'],
                $row[0]['showable'],
                $avgStars,
                $numReviews);
        }
        throw new \Exception('Book not found');
    }

    function select(string $query, ...$params): array
    {
        return $this->connection->query($query, $params);
    }

    function insert($object): void{
        $this->connection->query(
            "INSERT INTO books (title, author, genre, description, showable, user_id)VALUES (:title, :author, :genre, :description, :showable, :user_id)",
            $object->title, $object->author, $object->genre, $object->description, 0, $object->user_id);
    }

    function update($object): void
    {
        // TODO: Implement update() method.
    }

    function delete($object): void
    {
        $this->connection->execute("DELETE FROM reviews WHERE id = :id;", $object->id);
    }

    private function numberOfReviews(int $bookId): int{
        $row = $this->connection->query("SELECT COUNT(*) as reviewCount FROM reviews WHERE book_id = :id;", $bookId);
        return (int) ($row['reviewCount'] ?? 0);
    }

    private function getAverageStars(int $bookId): float{
        $row = $this->connection->query("SELECT ROUND(AVG(stars),1) as average FROM reviews WHERE book_id = :id;", $bookId);
        return (float) ($row['average'] ?? 0);
    }
}
<?php

namespace App\Mapper;

use App\Model\Review;
use Framework\Database\Interfaces\ConnectionInterface;
use Framework\Database\Interfaces\DataMapperInterface;

class ReviewMapper implements DataMapperInterface
{
    private ConnectionInterface $connection;

    public function __construct(ConnectionInterface $connection){
        $this->connection = $connection;
    }

    function get(int $id): object
    {
        $sql = 'SELECT * FROM reviews WHERE id = :id';
        $row = $this->connection->query($sql, $id);
        if($row){
            return new Review(
                $row[0]['id'],
                $row[0]['description'],
                $row[0]['stars'],
                $row[0]['book_id'],
                $row[0]['user_id']);
        }
        throw new \Exception('Book not found');
    }

    function select(string $query, ...$params): array
    {
        return $this->connection->query($query, ...$params);
    }

    function insert($object): void{
        $this->connection->execute(
            "INSERT INTO reviews (description, stars, book_id, user_id)VALUES (:description, :stars, :book_id, :user_id)",
            $object->description, $object->stars, $object->book_id, $object->user_id);
    }

    function update($object): void
    {
        // TODO: Implement update() method.
    }

    function delete($object): void
    {
        $this->connection->execute("DELETE FROM reviews WHERE book_id = :id;", $object->id);
    }
}
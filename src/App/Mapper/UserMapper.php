<?php

namespace App\Mapper;

use App\Model\USer;
use Framework\Database\Interfaces\ConnectionInterface;
use Framework\Database\Interfaces\DataMapperInterface;

class UserMapper implements DataMapperInterface
{
    private ConnectionInterface $connection;

    public function __construct(ConnectionInterface $connection){
        $this->connection = $connection;
    }

    function get(int $id): object
    {
        $sql = 'SELECT * FROM user WHERE id = :id';
        $row = $this->connection->query($sql, $id);
        if($row){
            return new User(
                $row[0]['id'],
                $row[0]['username'],
                $row[0]['email'],
                $row[0]['password'],
                $row[0]['role']);
        }
        throw new \Exception('Book not found');
    }

    function select(string $query, ...$params): array
    {
        return $this->connection->query($query, $params);
    }

    function insert($object): void{
    }

    function update($object): void
    {
        // TODO: Implement update() method.
    }

    function delete($object): void
    {
    }

}
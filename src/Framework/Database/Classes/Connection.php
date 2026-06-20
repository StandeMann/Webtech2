<?php
namespace Framework\Database\Classes;

use Framework\Database\Interfaces\ConnectionInterface;
use PDO;

class Connection implements ConnectionInterface
{
    private PDO $pdo;
    public function __construct()
    {
        $path = realpath(__DIR__);

        $database = $path . '/database.sqlite';

        $this->pdo = new PDO(
            'sqlite:' . $database
        );

        $this->pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        $this->pdo->exec(
            'PRAGMA foreign_keys = ON'
        );
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    function query(string $query, ...$params): array
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function execute(string $query, ...$params): int{
        if ($query[0] === 'S'){
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return count($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        else{
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->rowCount();
        }
    }

    function getLastInsertId(): int
    {
        return $this->pdo->lastInsertId();
    }
}
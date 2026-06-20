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

        $database = $path . '/../database.sqlite';


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

//    function query(string $query, ...$params): array
//    {
//        var_dump($query, $params);
//        exit();
//        $stmt = $this->pdo->prepare($query);
//        $stmt->execute($params);
//        return $stmt->fetchAll(PDO::FETCH_ASSOC);
//    }
    function query(string $query, ...$params): array
    {
        try {
            $stmt = $this->pdo->prepare($query);

            // flatten check (voorkomt [[7]]-bug)
            if (count($params) === 1 && is_array($params[0])) {
                $params = $params[0];
            }

            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "<pre>";
            echo $e->getMessage() . PHP_EOL;
            var_dump($query, $params);
            echo "</pre>";
            throw $e;
        }
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
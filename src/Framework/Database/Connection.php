<?php
namespace Framework\Database;

use PDO;

class Connection
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
}
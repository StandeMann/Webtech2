<?php

namespace App\Repository;

use Framework\Database\Connection;
use App\Model\User;
use PDO;

class UserFunctions
{
    private PDO $pdo;

    public function __construct(){
        $connection = new Connection();
        $this->pdo = $connection->getPDO();
    }

    public function createUser(string $username, string $email, string $password): void{
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $query = $this->pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $query->execute([$username, $email, $hash]);
    }

    public function getUser(int $id): User{
        $statement = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $statement->execute([$id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return new User(
            $row['id'],
            $row['username'],
            $row['email'],
            password_hash($row['password'], PASSWORD_DEFAULT),
            $row['role']
        );
    }

    public function getUserByEmail(string $email): User{
        $statement = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $statement->execute([$email]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return new User(
            $row['id'],
            $row['username'],
            $row['email'],
            $row['password'],
            $row['role']
        );
    }
}
<?php

namespace App\Repository;

use App\Model\User;
use Framework\Database\Interfaces\ConnectionInterface;

class UserFunctions
{
    private ConnectionInterface $connection;
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }
    public function createUser(string $username, string $email, string $password): void{
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $this->connection->execute("INSERT INTO users (username, email, password) VALUES (?, ?, ?)", $username, $email, $hash);
    }

    public function getUser(int $id): User{
        $row = $this->connection->query("SELECT * FROM users WHERE id = :id", $id);
        return new User(
            $row[0]['id'],
            $row[0]['username'],
            $row[0]['email'],
            password_hash($row[0]['password'], PASSWORD_DEFAULT),
            $row[0]['role']
        );
    }

    public function getUserByEmail(string $email): User{
        $row = $this->connection->query("SELECT * FROM users WHERE email = ?", $email);
        return new User(
            $row[0]['id'],
            $row[0]['username'],
            $row[0]['email'],
            $row[0]['password'],
            $row[0]['role']
        );
    }
}
<?php

namespace App\Repository;

use App\Mapper\UserMapper;
use App\Model\User;
use Framework\Database\Classes\IdentityMap;
use Framework\Database\Interfaces\ConnectionInterface;
use Framework\Database\Interfaces\DataMapperInterface;
use Framework\Database\Interfaces\IdentityMapInterface;
use Framework\Database\Interfaces\RepositoryInterface;

class UserRepository implements RepositoryInterface
{
    private DataMapperInterface $mapper;
    private IdentityMapInterface $identityMap;
    public function __construct(ConnectionInterface $connection){
        $this->mapper = new Usermapper($connection);
        $this->identityMap = new IdentityMap();
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

    public function createUser(string $username, string $email, string $password): void{
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $this->mapper->insert(new User(0, $username, $email, $hash, "user"));
    }
    public function getUser(int $id): User{
        $row = $this->mapper->select("SELECT * FROM users WHERE id = :id", $id);

//        $row = $this->connection->query("SELECT * FROM users WHERE id = :id", $id);
        return new User(
            $row[0]['id'],
            $row[0]['username'],
            $row[0]['email'],
            password_hash($row[0]['password'], PASSWORD_DEFAULT),
            $row[0]['role']
        );
    }

    public function getUserByEmail(string $email): User{
        $row = $this->mapper->select("SELECT * FROM users WHERE email = ?", $email);
        return new User(
            $row[0]['id'],
            $row[0]['username'],
            $row[0]['email'],
            $row[0]['password'],
            $row[0]['role']
        );
    }
}
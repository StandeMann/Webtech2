<?php

namespace App\Repository;

use Framework\Database\Classes\IdentityMap;
use Framework\Database\Interfaces\DataMapperInterface;
use Framework\Database\Interfaces\IdentityMapInterface;
use Framework\Database\Interfaces\RepositoryInterface;

class BookRepository implements RepositoryInterface {
    private DataMapperInterface $mapper;
    private IdentityMapInterface $identityMap;
    public function __construct(DataMapperInterface $mapper, IdentityMap $identityMap){
        $this->mapper = $mapper;
        $this->identityMap = $identityMap;
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
}
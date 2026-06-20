<?php

namespace Framework\Database\Classes;

use Framework\Database\Interfaces\IdentityMapInterface;

class IdentityMap implements IdentityMapInterface{
    private array $data = [];

    function has(int $id): bool
    {
        // TODO: Implement has() method.
        return isset($this->data[$id]);
    }

    function contains($object): bool
    {
        // TODO: Implement contains() method.
        return isset($this->data[$object]);
    }

    function get(int $id): object
    {
        // TODO: Implement get() method.
        return $this->data[$id];
    }

    function add(int $id, $object): void
    {
        // TODO: Implement add() method.
        $this->data[$id] = $object;
    }

    function remove($object): void
    {
        // TODO: Implement remove() method.
        unset($this->data[$object]);
    }
}
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
        return isset($this->data[$object]);
    }

    function get(int $id): object
    {
        return $this->data[$id];
    }

    function add(int $id, $object): void
    {
        $this->data[$id] = $object;
    }

    function remove($object): void
    {
        unset($this->data[$object]);
    }
}
<?php

namespace Framework\Database\Interfaces;

/**
 * A service that allows access to a collection of domain objects.
 * @template T
 */
interface RepositoryInterface
{
    /**
     * Get a single object by its primary key value.
     * @param int $id Primary key value.
     * @return T
     * @throws NotFoundException if the object was not found.
     */
    function get(int $id): object;

    /**
     * Store a new or existing object in the repository.
     * @param T $object
     */
    function save(object $object): void;

    /**
     * Remove an object from the repository.
     * @param T $object
     */
    function remove($object): void;
}
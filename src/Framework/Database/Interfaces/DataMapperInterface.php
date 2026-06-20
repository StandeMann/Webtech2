<?php

namespace Framework\Database\Interfaces;

/**
 * A service that maps domain objects to database records.
 * @template T
 */
interface DataMapperInterface
{
    /**
     * Select a single object by its primary key.
     * @param int $id
     * @return T
     * @throws NotFoundException if the object was not found.
     */
    function get(int $id): object;

    /**
     * Select a number of objects with a query.
     * @param string $query Query with placeholders.
     * @param mixed ...$params Parameters for the query.
     * @return array<T>
     */
    function select(string $query, mixed ...$params): array;

    /**
     * Insert a new object in the database.
     * @param T $object
     */
    function insert($object): void;

    /**
     * Update an existing object in the database.
     * @param T $object
     */
    function update($object): void;

    /**
     * Delete an object from the database.
     * @param T $object
     */
    function delete($object): void;
}
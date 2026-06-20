<?php
namespace Framework\Database\Interfaces;

/**
 * A data structure that stores unique objects based on their id.
 * @template T
 */
interface IdentityMapInterface
{
    /**
     * Checks whether an object with the given id exists in the identity map.
     * @param int $id
     * @return bool
     */
    function has(int $id): bool;

    /**
     * Checks whether the given object exists in the identity map.
     * @param T $object
     */
    function contains($object): bool;

    /**
     * Get an object from the identity map by its id.
     * @param int $id
     * @return T
     */
    function get(int $id): object;

    /**
     * Add an object with a given id to the identity map.
     * @param int $id
     * @param T $object
     */
    function add(int $id, $object): void;

    /**
     * Remove an object from the identity map.
     * @param T $object
     */
    function remove($object): void;
}
<?php

namespace Framework\Http;

/**
 * HTTP messages consist of requests from a client to a server and responses
 * from a server to a client. This interface defines the methods common to
 * each.
 *
 * Messages are considered immutable; all methods that might change state MUST
 * be implemented such that they retain the internal state of the current
 * message and return an instance that contains the changed state.
 *
 * @see http://www.ietf.org/rfc/rfc7230.txt
 * @see http://www.ietf.org/rfc/rfc7231.txt
 */
interface MessageInterface
{
    /**
     * Retrieves all message header values.
     *
     * The keys represent the header name, and each value is a string associated
     * with the header.
     *
     * @return string[] Returns an associative array of the message's headers.
     *     Each key MUST be a header name, and each value MUST be a string
     *     containing that header.
     */
    function getHeaders(): array;

    /**
     * Checks if a header exists by the given case-insensitive name.
     *
     * @param string $name Case-insensitive header field name.
     * @return bool Returns true if any header names match the given header
     *     name using a case-insensitive string comparison. Returns false if
     *     no matching header name is found in the message.
     */
    function hasHeader(string $name): bool;

    /**
     * Retrieves string with the value of a single header.
     *
     * If the header does not appear in the message, this method MUST return
     * an empty string.
     *
     * @param string $name Case-insensitive header field name.
     * @return string A string of values as provided for the given header
     *    concatenated together using a comma. If the header does not appear in
     *    the message, this method MUST return an empty string.
     */
    function getHeader(string $name): string;

    /**
     * Return an instance with the provided value replacing the specified header.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new and/or updated header and value.
     *
     * @param string $name Case-insensitive header field name.
     * @param string $value Header value(s).
     * @return static
     * @throws \InvalidArgumentException for invalid header names or values.
     */
    function withHeader(string $name, string $value): static;

    /**
     * Return an instance without the specified header.
     *
     * Header resolution MUST be done without case-sensitivity.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that removes
     * the named header.
     *
     * @param string $name Case-insensitive header field name to remove.
     * @return static
     */
    function withoutHeader(string $name): static;
}
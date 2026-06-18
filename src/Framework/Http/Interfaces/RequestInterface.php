<?php

namespace Framework\Http\Interfaces;

use Framework\Http\Classes\Uri;


/**
 * Representation of an incoming, server-side HTTP request.
 *
 * Per the HTTP specification, this interface includes properties for
 * each of the following:
 *
 * - HTTP method
 * - URI
 * - Headers
 * - Message body
 *
 * Additionally, it encapsulates all data as it has arrived at the
 * application from the CGI and/or PHP environment, including:
 *
 * - The values represented in $_SERVER.
 * - Any cookies provided (generally via $_COOKIE)
 * - Query string arguments (generally via $_GET, or as parsed via parse_str())
 * - Upload files, if any (as represented by $_FILES)
 * - Deserialized body parameters (generally from $_POST)
 *
 * Additionally, this interface recognizes the utility of introspecting a
 * request to derive and match additional parameters (e.g., via URI path
 * matching, decrypting cookie values, deserializing non-form-encoded body
 * content, matching authorization headers to users, etc). These parameters
 * are stored in an "attributes" property.
 *
 * Requests are considered immutable; all methods that might change state MUST
 * be implemented such that they retain the internal state of the current
 * message and return an instance that contains the changed state.
 */
interface RequestInterface extends MessageInterface
{
    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string Returns the request method.
     */
    function getMethod(): string;

    /**
     * Retrieves the URI instance.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.3
     * @return Uri Returns a Uri instance
     *     representing the URI of the request.
     */
    function getUri(): Uri;

    /**
     * Retrieve server parameters.
     *
     * Retrieves data related to the incoming request environment,
     * typically derived from PHP's $_SERVER superglobal. The data IS NOT
     * REQUIRED to originate from $_SERVER.
     *
     * @return array
     */
    function getServerParams(): array;

    /**
     * Retrieve cookies.
     *
     * Retrieves cookies sent by the client to the server.
     *
     * The data MUST be compatible with the structure of the $_COOKIE
     * superglobal.
     *
     * @return array
     */
    function getCookieParams(): array;
    
    /**
     * Retrieve query string arguments.
     *
     * Retrieves the deserialized query string arguments, if any.
     *
     * @return array
     */
    function getQueryParams(): array;

    /**
     * Retrieve file upload data.
     *
     * This method returns upload metadata as an array of instances of
     * Framework\Http\UploadedFileInterface.
     *
     * These values MUST be prepared from $_FILES during instantiation.
     *
     * This method MAY ignore or otherwise fail to handle nested arrays in the
     * $_FILES array.
     *
     * @return array An array of UploadedFileInterface instances; an empty
     *     array MUST be returned if no data is present.
     */
    function getUploadedFiles(): array;

    /**
     * Retrieve any parameters provided in the request body.
     *
     * If the request method is POST, this method MUST
     * return the contents of $_POST.
     *
     * Otherwise, this method MUST return null.
     *
     * @return null|array The deserialized body parameters as an array, if any.
     */
    function getParsedBody(): null|array;

    /**
     * Retrieve attributes derived from the request.
     *
     * The request "attributes" may be used to allow injection of any
     * parameters derived from the request: e.g., the results of path
     * match operations; the results of decrypting cookies; the results of
     * deserializing non-form-encoded message bodies; etc. Attributes
     * will be application and request specific, and CAN be mutable.
     *
     * @return mixed[] Attributes derived from the request.
     */
    function getAttributes(): array;

    /**
     * Retrieve a single derived request attribute.
     *
     * Retrieves a single derived request attribute as described in
     * getAttributes(). If the attribute has not been previously set, returns
     * the default value as provided.
     *
     * This method obviates the need for a hasAttribute() method, as it allows
     * specifying a default value to return if the attribute is not found.
     *
     * @see getAttributes()
     * @param string $name The attribute name.
     * @param mixed $default Default value to return if the attribute does not exist.
     * @return mixed
     */
    function getAttribute(string $name, mixed $default = null): mixed;

    /**
     * Return an instance with the specified derived request attribute.
     *
     * This method allows setting a single derived request attribute as
     * described in getAttributes().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated attribute.
     *
     * @see getAttributes()
     * @param string $name The attribute name.
     * @param mixed $value The value of the attribute.
     * @return static
     */
    function withAttribute(string $name, mixed $value): static;

    /**
     * Return an instance that removes the specified derived request attribute.
     *
     * This method allows removing a single derived request attribute as
     * described in getAttributes().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that removes
     * the attribute.
     *
     * @see getAttributes()
     * @param string $name The attribute name.
     * @return static
     */
    function withoutAttribute(string $name): static;
}
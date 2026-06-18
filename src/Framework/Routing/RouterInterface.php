<?php

namespace Framework\Routing;

use Framework\Http\Interfaces\RequestInterface;

/**
 * A service that maps requests to request handlers.
 */
interface RouterInterface
{
    /**
     * Find a suitable request handler for the current request.
     *
     * @return callable A request handler configured to correctly pass any
     *     routing parameters. This callable should accept a single parameter
     *     containing the request and return the appropriate response.
     *
     * @throws \DomainException If no route was found for the request.
     */
    function route(RequestInterface $request): ?array;
}
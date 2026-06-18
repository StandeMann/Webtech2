<?php

namespace Framework\Kernel;

use Framework\Http\Interfaces\RequestInterface;
use Framework\Http\Interfaces\ResponseInterface;

/**
 * Handles a server request and produces a response.
 *
 * A kernel processes an HTTP request in order to produce an
 * HTTP response.
 */
interface KernelInterface
{
    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     *
     * @param RequestInterface $request The request.
     * @return ResponseInterface The response.
     */
    function handle(RequestInterface $request): ResponseInterface;
}
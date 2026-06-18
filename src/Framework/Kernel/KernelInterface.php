<?php

namespace Framework\Kernel;

use Framework\Templating\Request;

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
     * @param array $get The contents of the $_GET superglobal. 
     * @param array $post The contents of the $_POST superglobal. 
     * @return string The response as a string.
     */
    public function handle(Request $request): string;
}
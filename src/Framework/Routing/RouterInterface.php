<?php

namespace Framework\Routing;

use Framework\Http\RequestInterface;

interface RouterInterface
{
    function route(RequestInterface $request): callable;
}

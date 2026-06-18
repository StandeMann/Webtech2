<?php

namespace Framework\Routing;
use Framework\Http\Classes\Request;
use Framework\Http\Interfaces\RequestInterface;

class Router implements RouterInterface
{
    private array $routes = [];

    public function add(
        string $method,
        string $path,
        callable $controller
    ): void {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller
        ];
    }

    public function route(RequestInterface $request): ?array
    {
        foreach ($this->routes as $route) {

            $pattern = preg_replace(
                '#\{([a-zA-Z]+)\}#',
                '([^/]+)',
                $route['path']
            );

            $pattern = '#^' . $pattern . '$#';
            if (
                $route['method'] === $request->getMethod() &&
                preg_match($pattern, $request->getUri()->__toString(), $matches)
            ) {

                array_shift($matches);

                preg_match_all(
                    '#\{([a-zA-Z]+)\}#',
                    $route['path'],
                    $paramNames
                );

                $params = array_combine(
                    $paramNames[1],
                    $matches
                );

                return [
                    'controller' => $route['controller'],
                    'params' => $params
                ];
            }
        }
        throw new \DomainException("Domein niet gevonden");
    }
}
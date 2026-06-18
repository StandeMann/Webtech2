<?php

namespace Framework\Routing;
use Framework\Http\Request;

class Router
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

    public function match(Request $request): ?array
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
                preg_match($pattern, $request->getPath(), $matches)
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

        return null;
    }
}
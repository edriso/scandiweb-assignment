<?php

namespace App\Core;

class Router
{
    private $routes = [];

    // made add public incase we wanted to use it like this:
    // $router->add('GET', '/', 'controller.php');
    public function add($method, $uri, $handler)
    {
        $controller = $handler['controller'] ?? $handler;
        $action = $handler['action'] ?? 'index';

        $this->routes[] = compact('method', 'uri', 'controller', 'action');

        return $this;
    }

    public function get($uri, $handler)
    {
        return $this->add('GET', $uri, $handler);
    }

    public function post($uri, $handler)
    {
        return $this->add('POST', $uri, $handler);
    }

    public function delete($uri, $handler)
    {
        return $this->add('DELETE', $uri, $handler);
    }

    public function patch($uri, $handler)
    {
        return $this->add('PATCH', $uri, $handler);
    }

    public function put($uri, $handler)
    {
        return $this->add('PUT', $uri, $handler);
    }

    public function route($uri, $method)
    {
        if (strpos($uri, '/api/') !== 0) {
            return require(base_path('public/index.html'));
        }

        foreach ($this->routes as $route) {
            if (
                $route['uri'] === $uri &&
                $route['method'] === strtoupper($method)
            ) {
                $controllerClass = generate_controller_namespace($route['controller']);
                $action = $route['action'];
                try {
                    $controller = new $controllerClass();
                    $controller->$action();
                    exit;
                } catch (\Error) {
                    Response::abort('Not found');
                }
            }
        }

        Response::abort();
    }
}
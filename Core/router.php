<?php

namespace Core;

class Router {
    private $routes = [];

    // made add public incase we wanted to use it like this:
    // $router->add('GET', '/', 'controller.php');
    public function add($method, $uri, $handler) {
        $controller = $handler['controller'] ?? $handler;
        $action = $handler['action'] ?? 'index';

        $this->routes[] = compact('method','uri','controller', 'action');
        
        return $this;
    }

    public function get($uri, $handler) {
        return $this->add('GET', $uri, $handler);
    }

    public function post($uri, $handler) {
        return $this->add('POST', $uri, $handler);
    }

    public function delete($uri, $handler) {
        return $this->add('DELETE', $uri, $handler);
    }

    public function patch($uri, $handler) {
        return $this->add('PATCH', $uri, $handler);
    }

    public function put($uri, $handler) {
        return $this->add('PUT', $uri, $handler);
    }

    public function route($uri, $method) {
        if (strpos($uri, '/api/') !== 0) {
            return require(BASE_PATH . 'public/index.html');
        }

        foreach ($this->routes as $route) {
            if (
                $route['uri'] === $uri &&
                $route['method'] === strtoupper($method)
            ) {
                $controllerClass = 'Http\Controllers\\' . $route['controller'];
                $action = $route['action'];
                try {
                    $controller = new $controllerClass();
                    $controller->$action();
                    exit;
                } catch (\Error) {
                    self::abort('Not found');
                }
            }
        }

        self::abort();
    }

    public static function abort(
        $errorMessage = 'Resource not found', $statusCode = 404
    ) {
        $response = [
            'message' => $errorMessage,
        ];
        
        self::sendJsonResponse($response, $statusCode);
    }
    
    public static function sendJsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');

        if ($statusCode !== 204) {
            $response = [];
    
            if (is_array($data) && isset($data['message'])) {
                $response['message'] = $data['message'];
            } else {
                $dataKey = array_keys($data)[0] ?? null;
    
                if ($dataKey !== null && is_array(reset($data[$dataKey]))) {
                    $response['data']['results'] = count($data[$dataKey]);
                }
                
                $response['data'][$dataKey] = $data[$dataKey];
            }
    
            echo json_encode($response);
        }
    
        exit;
    }
}
<?php

$routes = require(BASE_PATH . 'routes.php');

function handleApiRequest($uri, $routes) {
    if (strpos($uri, '/api/') === 0) {
        $routeInfo = $routes[$uri] ?? null;
        if ($routeInfo) {
            $controllerClassName = 'Http\Controllers\\' . $routeInfo['controller'];
            $action = $routeInfo['action'] ?? 'index';
            callControllerAction($controllerClassName, $action);    
        } else {
            abort();
        }
    } else {
        require(BASE_PATH . 'public/index.html');
        exit;
    }
}

function callControllerAction($controllerClassName, $action) {
    try {
        $controller = new $controllerClassName();
        $controller->$action();
    } catch (\Error) {
        abort('Not found');
    }
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

handleApiRequest($uri, $routes);
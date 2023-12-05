<?php

$routes = require(BASE_PATH . 'routes.php');

function handleApiRequest($uri, $routes) {
    if (strpos($uri, '/api/') === 0) {
        $routeInfo = $routes[$uri] ?? null;
        if ($routeInfo) {
            $controllerClassName = 'Http\Controllers\\' . $routeInfo['controller'];
            $method = $routeInfo['method'] ?? 'index';
            callControllerMethod($controllerClassName, $method);    
        } else {
            abort();
        }
    } else {
        require(BASE_PATH . 'public/index.html');
        exit;
    }
}

function callControllerMethod($controllerClassName, $method) {
    try {
        $controller = new $controllerClassName();
        $controller->$method();
    } catch (\Error) {
        abort('Not found');
    }
}

function abort(
    $errorMessage = 'Resource not found', $statusCode = 404
) {
    $response = [
        'error' => [
            'message' => $errorMessage,
        ],
    ];
    
    sendJsonResponse($response, $statusCode);
}

function sendJsonResponse($responseData, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');

    $response = [
        'status' => $statusCode,
    ];

    if (is_array($responseData) && isset($responseData['error'])) {
        $response['error'] = $responseData['error'];
    } else {
        $response['data'] = $responseData;
    }

    echo json_encode($response);
    exit;
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

handleApiRequest($uri, $routes);
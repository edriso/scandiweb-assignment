<?php

$routes = require(BASE_PATH . 'routes.php');

function handleApiRequest($uri, $routes) {
    if (strpos($uri, '/api/') === 0) {
        if (array_key_exists($uri, $routes)) {
            return require($routes[$uri]);
        } else {
            abort();
        }
    } else {
        require(CLIENT_PATH . 'index.html');
        die();
    }
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

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

handleApiRequest($uri, $routes);
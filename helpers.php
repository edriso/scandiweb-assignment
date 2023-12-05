<?php

function base_path($path) {
    return BASE_PATH . $path;
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

function dd($value) {
    echo "<pre>";
    var_dump($value);
    echo "</pre>";

    die();
}
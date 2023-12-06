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
        if (is_array($responseData) && isset($responseData[0])) {
            $response['results'] = count($responseData);
        }
        $response['data'] = $responseData;
    }

    echo json_encode($response);
    exit;
}

function parseJsonRequest() {
    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData, true);

    if ($data === null) {
        abort('Invalid JSON data', 400);
    }

    return $data;
}

function dd($value) {
    echo "<pre>";
    var_dump($value);
    echo "</pre>";

    die();
}
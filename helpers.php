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

function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');

    if ($statusCode !== 204) {
        $response = [];

        if (is_array($data) && isset($data['error'])) {
            $response['error'] = $data['error'];
        } else {
            $dataKey = array_keys($data)[0] ?? null;

            if ($dataKey !== null && is_array(reset($data[$dataKey]))) {
                $response['data']['results'] = count($data[$dataKey]);
                $response['data'][$dataKey] = $data[$dataKey];
            } else {
                $response['data'] = $data;
            }
        }

        echo json_encode($response);
    }

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
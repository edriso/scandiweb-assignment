<?php

namespace Core;

class Response {
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
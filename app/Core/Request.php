<?php

namespace App\Core;

class Request
{
    public static function parseJsonRequest()
    {
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        if ($data === null) {
            Response::abort('Invalid JSON data', 400);
        }

        return $data;
    }
}
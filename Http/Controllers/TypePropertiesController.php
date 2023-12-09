<?php

namespace Http\Controllers;

use Core\Database;

class TypePropertiesController
{
    public function show() {
        $typeId = filter_input(INPUT_GET, 'type_id', FILTER_VALIDATE_INT);
        if ($typeId === false || $typeId === null) {
            abort('Invalid or missing type_id parameter', 400);
        }
        
        $db = new Database();
        $query = 'SELECT id, name, unit FROM type_properties WHERE type_id = :type_id';
        $properties = $db->query($query, [
            'type_id' => $typeId,
        ])->getOrFail();
        sendJsonResponse($properties);
    }
}
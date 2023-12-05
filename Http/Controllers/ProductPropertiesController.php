<?php

namespace Http\Controllers;

use Core\Database;

class ProductPropertiesController
{
    public function show()
    {
        $db = new Database();
        $properties = $db->query('SELECT * FROM type_properties WHERE type_id = :type_id', [
            'type_id' => $_GET['type_id'],
        ])->getOrFail();

        sendJsonResponse($properties);
    }

}
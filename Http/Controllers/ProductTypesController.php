<?php

namespace Http\Controllers;

use Core\Database;

class ProductTypesController {
    public function index() {
        $db = new Database();
        $products = $db->query('SELECT
            id, type_name AS name, measure_name, measure_unit
            FROM product_types')->get();

        sendJsonResponse($products);
    }
}
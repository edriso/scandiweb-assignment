<?php

namespace Http\Controllers;

use Core\Response;
use Core\Database;

class ProductTypesController {
    public function index() {
        $db = new Database();
        $products = $db->query(
            'SELECT
                id, name, measure_name, measure_unit
            FROM product_types')->get();

        Response::sendJsonResponse($products);
    }
}
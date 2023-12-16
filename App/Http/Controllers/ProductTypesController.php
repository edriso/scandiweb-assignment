<?php

namespace App\Http\Controllers;

use App\Core\Response;
use App\Core\Database;

class ProductTypesController
{
    public function index()
    {
        $db = new Database();
        $products = $db->query(
            'SELECT
                id, name, measure_name, measure_unit
            FROM product_types')->get();

        Response::sendJsonResponse($products);
    }
}
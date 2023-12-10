<?php

namespace Http\Controllers;

use Core\Request;
use Core\Response;
use Http\Models\Product;

class ProductsController {
    public function index() {
        $products = Product::getAll();
        Response::sendJsonResponse(['products' => $products]);
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $body = Request::parseJsonRequest();
            if (!isset($body['type_id'])) {
                Response::abort('Missing type_id', 400);
            }
            $newProduct = Product::create($body);
            Response::sendJsonResponse(['product' => $newProduct], 201);
        }
    }

    public function destroy() {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            if (!isset($_GET['productIds'])) {
                Response::abort('Invalid request. Missing product IDs.', 400);
            }
            
            $productIds = explode(',', $_GET['productIds']);
            Product::deleteByIds($productIds);
            Response::sendJsonResponse([], 204);
        }
    }
}
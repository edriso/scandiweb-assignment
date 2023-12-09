<?php

namespace Http\Controllers;

use Http\Models\Product;

class ProductsController {
    public function index() {
        $products = Product::getAll();
        sendJsonResponse(['products' => $products]);
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $body = parseJsonRequest();
            if (!isset($body['type_id'])) {
                abort('Missing type_id', 400);
            }
            $newProduct = Product::create($body);
            sendJsonResponse(['product' => $newProduct], 201);
        }
    }

    public function destroy() {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            if (!isset($_GET['productIds'])) {
                abort('Invalid request. Missing product IDs.', 400);
            }
            
            $productIds = explode(',', $_GET['productIds']);
            Product::deleteByIds($productIds);
            sendJsonResponse([], 204);
        }
    }
}
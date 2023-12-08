<?php

namespace Http\Controllers;

use Http\Models\Product;

class ProductsController {
    public function index() {
        $products = Product::getAll();
        sendJsonResponse($products);
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $body = parseJsonRequest();
            $newProduct = Product::create($body);
            sendJsonResponse(['product' => $newProduct], 201);
        }
    }

    public function destroy() {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $body = parseJsonRequest();
            $productIds = reset($body);
            if (!is_array($productIds) || empty($productIds)) {
                abort('Invalid request. Expected an array of product IDs.', 400);
            }
            Product::deleteByIds($productIds);
            sendJsonResponse([], 204);
        }
    }
}
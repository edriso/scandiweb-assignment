<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Http\Models\Product;

class ProductsController
{
    public function index()
    {
        $products = Product::getAll();
        Response::sendJsonResponse(['products' => $products]);
    }

    public function store()
    {
        $body = Request::parseJsonRequest();
        $newProduct = Product::create($body);
        Response::sendJsonResponse(['product' => $newProduct], 201);
    }

    public function destroy()
    {
        if (!isset($_GET['productIds'])) {
            Response::abort('Invalid request. Missing productIds parameter', 400);
        }

        $productIds = explode(',', $_GET['productIds']);
        Product::deleteByIds($productIds);
        Response::sendJsonResponse(['message' => 'Success!'], 200);
    }
}
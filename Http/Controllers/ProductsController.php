<?php

namespace Http\Controllers;

use Core\Database;

class ProductPropertiesController
{
    public function index()
    {
        $db = new Database();
        $products = $db->query('SELECT id, sku, name, price FROM products')->get();

        sendJsonResponse($products);
    }
    
    public function store()
    {
        echo 'store product';
    }

}
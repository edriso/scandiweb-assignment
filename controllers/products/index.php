<?php

$env = parse_ini_file(BASE_PATH . '.env');

$db = new Database($env);
$products = $db->query('SELECT id, sku, name, price FROM products')->get();

sendJsonResponse($products);
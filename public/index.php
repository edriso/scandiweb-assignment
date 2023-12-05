<?php

const BASE_PATH = __DIR__.'/../';
const CLIENT_PATH = __DIR__.'/';
$env = parse_ini_file(BASE_PATH . '.env');

require(BASE_PATH . 'helpers.php');
require(BASE_PATH . 'Database.php');
require(BASE_PATH . 'router.php');

$db = new Database($env);
$products = $db->query('SELECT id, sku, name, price FROM products')->get();
// $products = $db->query('SELECT * FROM products where id = 1 limit 1')->fetch(pdo::FETCH_ASSOC);
dd($products);
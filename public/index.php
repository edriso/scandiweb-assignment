<?php

header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');

const BASE_PATH = __DIR__.'/../';
$config = require(BASE_PATH . 'config.php');

if ($config['app']['env'] === 'development') {
    header('Access-Control-Allow-Origin: *');

    // If the request is an OPTIONS request (pre-flight check)
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header("HTTP/1.1 200 OK");
        exit;
    }
}

require(BASE_PATH . 'vendor/autoload.php');
require(BASE_PATH . 'Core/helpers.php');

$router = new \Core\Router();
$routes = require(BASE_PATH . 'routes.php');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);
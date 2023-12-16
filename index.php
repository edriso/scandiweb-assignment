<?php

header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Origin: *');

// If the request is an OPTIONS request (pre-flight check)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit;
}

const BASE_PATH = __DIR__ . '/';
$config = require(BASE_PATH . 'App/config.php');

require(BASE_PATH . 'vendor/autoload.php');
require(BASE_PATH . 'App/helpers.php');

$router = new \App\Core\Router();
$routes = require(app_path('routes.php'));

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);
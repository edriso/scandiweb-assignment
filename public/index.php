<?php

const BASE_PATH = __DIR__.'/../';

$config = require(BASE_PATH . 'config.php');
if ($config['app']['env'] === 'development') {
    header('Access-Control-Allow-Origin: *');
}
header('Access-Control-Allow-Methods: GET, POST, DELETE');

require(BASE_PATH . 'vendor/autoload.php');
require(BASE_PATH . 'Core/helpers.php');

$router = new \Core\Router();
$routes = require(BASE_PATH . 'routes.php');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
$router->route($uri, $method);
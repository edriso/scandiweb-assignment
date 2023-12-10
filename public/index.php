<?php

const BASE_PATH = __DIR__.'/../';

$config = require(BASE_PATH . 'config.php');
if ($config['app']['env'] === 'development') {
    header('Access-Control-Allow-Origin: *');
}
header('Access-Control-Allow-Methods: GET, POST, DELETE');

require(BASE_PATH . 'vendor/autoload.php');

require(BASE_PATH . 'Core/helpers.php');
require(BASE_PATH . 'Core/Database.php');
require(BASE_PATH . 'router.php');
<?php

$router->get('/api/v1/products', ['controller' => 'ProductsController']);
$router->post('/api/v1/products', ['controller' => 'ProductsController', 'action' => 'store']);
$router->delete('/api/v1/products', ['controller' => 'ProductsController', 'action' => 'destroy']);

$router->get('/api/v1/types', ['controller' => 'ProductTypesController']);

$router->get('/api/v1/properties', ['controller' => 'TypePropertiesController', 'action' => 'show']);
<?php

return [
    '/api/v1/products' => ['controller' => 'ProductsController'],
    '/api/v1/product' => ['controller' => 'ProductsController', 'action' => 'store'],
    '/api/v1/product-types' => ['controller' => 'ProductTypesController'],
    '/api/v1/type-properties' => ['controller' => 'TypePropertiesController', 'action' => 'show'],
];
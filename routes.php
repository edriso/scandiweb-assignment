<?php

return [
    '/api/v1/products' => ['controller' => 'ProductsController', 'method' => 'index'],
    '/api/v1/product' => ['controller' => 'ProductsController', 'method' => 'store'],
    '/api/v1/product-types' => ['controller' => 'ProductTypesController', 'method' => 'index'],
    '/api/v1/product-properties' => ['controller' => 'ProductPropertiesController', 'method' => 'show'],
];

// return [
//     '/api/v1/products' => '../controllers/products/index.php',
//     '/api/v1/product' => '../controllers/products/store.php',
//     '/api/v1/product-types' => '../controllers/productTypes/index.php',
//     '/api/v1/product-properties' => '../controllers/productProperties/show.php',
// ];
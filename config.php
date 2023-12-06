<?php

$env = parse_ini_file(BASE_PATH . '.env');

return [
    'database' => [
        'host' => $env['DB_HOST'],
        'port' => $env['DB_PORT'],
        'dbname' => $env['DB_NAME'],
        'user' => $env['DB_USER'],
        'password' => $env['DB_PASSWORD'],
        'charset' => 'utf8mb4',
    ],
];
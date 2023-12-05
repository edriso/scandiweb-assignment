<?php

namespace Core;

use PDO;
use PDOException;

class Database {
    private $connection;
    private $statement;
    
    public function __construct()
    {
        try {
            $env = parse_ini_file(BASE_PATH . '.env');
            $config = [
                'host' => $env['DB_HOST'],
                'port' => $env['DB_PORT'],
                'dbname' => $env['DB_NAME'],
                'user' => $env['DB_USER'],
                'password' => $env['DB_PASSWORD'],
                'charset' => 'utf8mb4',
            ];

            $dsn = 'mysql:' . http_build_query($config, '', ';');

            $this->connection = new PDO($dsn, options: [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch (PDOException $e) {
            $this->handleError('Database connection error', $e->getMessage());
        }
    }

    public function query($query, $params = []) {
        try {
            $this->statement = $this->connection->prepare($query);
            $this->statement->execute($params);
            return $this;
        } catch (PDOException $e) {
            $this->handleError('Database query error', $e->getMessage());
        }
    }

    public function get() {
        return $this->statement->fetchAll();
    }

    public function find() {
        return $this->statement->fetch();
    }

    private function handleError($errorMessage, $errorDetails)
    {
        http_response_code(500);
        header('Content-Type: application/json');

        $response = [
            'error' => [
                'status' => 500,
                'message' => $errorMessage,
                'details' => $errorDetails,
            ],
        ];

        echo json_encode($response);
        exit;
    }
}
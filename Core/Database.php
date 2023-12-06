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
            $config = require(BASE_PATH . 'config.php');

            $dsn = 'mysql:' . http_build_query($config['database'], '', ';');

            $this->connection = new PDO($dsn, options: [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch (PDOException $e) {
            $this->handleError('Database connection error');
        }
    }

    public function query($query, $params = []) {
        try {
            $this->statement = $this->connection->prepare($query);
            $this->statement->execute($params);
            return $this;
        } catch (PDOException $e) {
            // $this->handleError('Database query error', $e->getMessage());
            $this->handleError('Database query error');
        }
    }

    public function get() {
        return $this->statement->fetchAll();
    }
    
    public function getOrFail() {
        $result =  $this->statement->fetchAll();
        
        if (empty($result)) {
            abort('Invalid request', 400);
        }

        return $result;
    }

    public function find() {
        return $this->statement->fetch();
    }

    public function findOrFail() {
        $result =  $this->statement->fetch();
        
        if (! $result) {
            abort('Invalid request', 400);
        }

        return $result;
    }

    private function handleError($errorMessage)
    {
        http_response_code(500);
        header('Content-Type: application/json');

        $response = [
            'error' => [
                'status' => 500,
                'message' => $errorMessage,
                // 'details' => $errorDetails,
            ],
        ];

        echo json_encode($response);
        exit;
    }
}
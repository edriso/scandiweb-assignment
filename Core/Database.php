<?php

namespace Core;

use PDO;
use PDOException;

class Database {
    private $connection;
    private $statement;
    private $config;
    
    public function __construct() {
        try {
            $this->config = require(BASE_PATH . 'config.php');
            $dsn = 'mysql:' . http_build_query($this->config['database'], '', ';');

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
    
    public function getOrFail() {
        $result =  $this->statement->fetchAll();
        
        if (empty($result)) {
            Response::abort('Invalid request', 400);
        }

        return $result;
    }

    public function getOne() {
        return $this->statement->fetch();
    }

    public function getOneOrFail() {
        $result =  $this->statement->fetch();
        
        if (!$result) {
            Response::abort('Invalid request', 400);
        }

        return $result;
    }

    private function handleError($errorMessage, $errorDetails = '') {
        http_response_code(500);
        header('Content-Type: application/json');

        $response = [
            'message' => $errorMessage,
        ];

        if (preg_match("/Duplicate entry '(.*?)' for key '(.*?)'/", $errorDetails, $matches)) {
            $value = $matches[1];
            $fieldName = strtoupper(preg_replace('/^[^.]+\\./', '', $matches[2]));

            if (!empty($fieldName)) {
                $response['message'] = "Duplicate value '$value' for field $fieldName";
            } else {
                $response['message'] = 'Duplicate value for a field.';
            }
        } else if (preg_match("/Incorrect (.*?) value: '(.*?)' for column '(.*?)'/", $errorDetails, $matches)) {
            $value = $matches[2];
            $fieldName = strtoupper($matches[3]);
    
            $response['message'] = "Incorrect value '$value' for field $fieldName";
        }

        if ($this->config['app']['env'] !== 'production') {
            $response['details'] = $errorDetails;
        }

        echo json_encode($response);
        exit;
    }
}
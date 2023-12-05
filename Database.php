<?php

// use PDO;

class Database {
    private $connection;
    private $statement;

    public function __construct($env)
    {
        dd($env);
        try {
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
            $this->handleConnectionError($e->getMessage());
        }
    }

    public function query($query, $params = []) {
        $this->statement = $this->connection->prepare($query);

        $this->statement->execute($params);

        return $this;
    }

    public function get() {
        return $this->statement->fetchAll();
    }

    public function find() {
        return $this->statement->fetch();
    }

    private function handleConnectionError($errorMessage)
    {
        http_response_code(500);
        header('Content-Type: application/json');

        $response = [
            'error' => [
                'status' => 500,
                'message' => 'Database connection error',
                'details' => $errorMessage,
            ],
        ];

        echo json_encode($response);
        exit;
    }
}
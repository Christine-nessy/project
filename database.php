<?php

class Database {
    private $host = 'localhost:3308';
    private $dbname = 'user_data';
    private $username = 'root'; // Change this based on your database configuration
    private $password = 'root'; // Your database password
    private $connection;

    public function __construct() {
        try {
            $this->connection = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}
?>

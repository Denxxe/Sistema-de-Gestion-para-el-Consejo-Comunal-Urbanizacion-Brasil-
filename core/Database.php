<?php
namespace App\Core;
use PDO;
use PDOException;
class Database {
    private $conn;

    public function connect() {
        try {
            $driver = $_ENV['DB_DRIVER'];       // pgsql o mysql
            $host   = $_ENV['DB_HOST'];
            $port   = $_ENV['DB_PORT'];
            $dbname = $_ENV['DB_NAME'];
            $user   = $_ENV['DB_USER'];
            $pass   = $_ENV['DB_PASS'];

            $dsn = "$driver:host=$host;port=$port;dbname=$dbname";
            $this->conn = new PDO($dsn, $user, $pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $this->conn;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}

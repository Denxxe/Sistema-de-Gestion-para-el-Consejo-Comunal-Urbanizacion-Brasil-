<?php
namespace App\Core;
use PDO;
use PDOException;
use App\Core\DatabaseInterface;

class Database implements DatabaseInterface {
    private $conn;

    public function connect(): PDO {
        try {
            if ($this->conn === null) {
                $driver = $_ENV['DB_DRIVER'];
                $host   = $_ENV['DB_HOST'];
                $port   = $_ENV['DB_PORT'];
                $dbname = $_ENV['DB_NAME'];
                $user   = $_ENV['DB_USER'];
                $pass   = $_ENV['DB_PASS'];

                $dsn = "$driver:host=$host;port=$port;dbname=$dbname";
                $this->conn = new PDO($dsn, $user, $pass);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            }
            return $this->conn;
        } catch (PDOException $e) {
            error_log("Error de conexión: " . $e->getMessage());
            throw new \RuntimeException("Error al conectar con la base de datos", 0, $e);
        }
    }
}

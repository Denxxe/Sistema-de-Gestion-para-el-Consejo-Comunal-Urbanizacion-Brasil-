<?php
class Database {
    private $conn;

    public function connect() {
        try {
            $dsn = "{$_ENV['DB_DRIVER']}:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']}";
            $this->conn = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            return null;
        }
    }
}

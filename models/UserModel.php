<?php
namespace App\models;

use PDO;
use App\Core\Database;
use PDOException;

class UserModel
{
    private PDO $db;
    private string $cedula;
    private string $password;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function setCredentials(string $cedula, string $password): void
    {
        $this->cedula = $cedula;
        $this->password = $password;
    }

    public function authenticate(): ?array
    {
        try {
            $sql = "SELECT p.id_persona, p.cedula, p.nombres, p.apellidos, p.password 
                    FROM persona p 
                    WHERE p.cedula = :cedula 
                    AND p.activo = true";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':cedula' => $this->cedula
            ]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al autenticar usuario: " . $e->getMessage());
            return null;
        }
    }

    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword(string $hashedPassword, string $password): bool
    {
        return password_verify($password, $hashedPassword);
    }
}

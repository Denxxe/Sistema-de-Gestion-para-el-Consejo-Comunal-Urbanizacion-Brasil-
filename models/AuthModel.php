<?php
namespace App\models;

use PDO;
use App\Core\Database;
use PDOException;
use Exception;

class AuthModel {
    private PDO $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function login(string $cedula, string $contrasena): ?array {
        try {
            $sql = "SELECT u.*, p.id_persona, p.cedula, p.nombres, p.apellidos
                    FROM usuario u 
                    JOIN persona p ON u.id_persona = p.id_persona
                    WHERE p.cedula = :cedula 
                    AND u.activo = true";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':cedula', $cedula, PDO::PARAM_STR);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($contrasena, $user['contrasena'])) {
                return $user;
            }
            
            return null;
        } catch (PDOException $e) {
            throw new Exception("Error al iniciar sesión: " . $e->getMessage());
        }
    }

    public function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function validatePassword(string $password): bool {
        if (strlen($password) < 8) {
            throw new Exception("La contraseña debe tener al menos 8 caracteres");
        }
        if (!preg_match('/[A-Z]/', $password)) {
            throw new Exception("La contraseña debe tener al menos una mayúscula");
        }
        if (!preg_match('/[a-z]/', $password)) {
            throw new Exception("La contraseña debe tener al menos una minúscula");
        }
        if (!preg_match('/[0-9]/', $password)) {
            throw new Exception("La contraseña debe tener al menos un número");
        }
        return true;
    }
}
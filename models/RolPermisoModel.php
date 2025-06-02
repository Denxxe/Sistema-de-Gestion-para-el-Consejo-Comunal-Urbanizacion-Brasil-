<?php
namespace App\models;

use PDO;
use App\Core\Database;
use PDOException;

class RolPermisoModel {
    private PDO $db;

    // Atributos
    private int $id_rol;
    private int $id_permiso;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Métodos CRUD
    public function crear(): bool {
        try {
            $sql = "INSERT INTO rol_permiso (id_rol, id_permiso)
                    VALUES (:id_rol, :id_permiso)
                    RETURNING id_rol, id_permiso";
            
            $stmt = $this->db->prepare($sql);
        
            $stmt->bindValue(':id_rol', $this->id_rol);
            $stmt->bindValue(':id_permiso', $this->id_permiso);
        
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result['id_rol']) && isset($result['id_permiso'])) {
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error al crear la relación rol-permiso: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function eliminar(): bool {
        try {
            $sql = "DELETE FROM rol_permiso 
                    WHERE id_rol = :id_rol AND id_permiso = :id_permiso 
                    RETURNING id_rol, id_permiso";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id_rol", $this->id_rol);
            $stmt->bindValue(":id_permiso", $this->id_permiso);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && isset($result["id_rol"]) && isset($result["id_permiso"])) {
                return true;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Error al eliminar la relación rol-permiso: " . $e->getMessage());
            throw $e;
        }
    }

    // Getters y Setters
    public function getId_rol(): int {
        return $this->id_rol;
    }

    public function setId_rol(int $id_rol): void {
        $this->id_rol = $id_rol;
    }

    public function getId_permiso(): int {
        return $this->id_permiso;
    }

    public function setId_permiso(int $id_permiso): void {
        $this->id_permiso = $id_permiso;
    }
}

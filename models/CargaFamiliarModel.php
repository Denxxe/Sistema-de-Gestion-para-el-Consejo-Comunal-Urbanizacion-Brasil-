<?php
namespace App\models;

use PDO;
use App\Core\Database;
use PDOException;

class CargaFamiliarModel {
    private PDO $db;

    // Atributos
    private int $id_carga;
    private int $id_habitante;
    private int $id_jefe;
    private string $parentesco;
    private bool $activo = true;
    private string $fecha_registro;
    private string $fecha_actualizacion;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Métodos CRUD
    public function crear(): bool {
        try {
            $sql = "INSERT INTO carga_familiar (id_habitante, id_jefe, parentesco, fecha_registro, fecha_actualizacion)
                    VALUES (:id_habitante, :id_jefe, :parentesco, :fecha_registro, :fecha_actualizacion)
                    RETURNING id_carga";
            
            $stmt = $this->db->prepare($sql);

            $ahora = date('Y-m-d H:i:s');
            $this->fecha_registro = $ahora;
            $this->fecha_actualizacion = $ahora;
        
            $stmt->bindValue(':id_habitante', $this->id_habitante);
            $stmt->bindValue(':id_jefe', $this->id_jefe);
            $stmt->bindValue(':parentesco', $this->parentesco);
            $stmt->bindValue(':fecha_registro', $this->fecha_registro);
            $stmt->bindValue(':fecha_actualizacion', $this->fecha_actualizacion);
        
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result['id_carga'])) {
                $this->id_carga = $result['id_carga'];
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error al crear la carga familiar: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function eliminar(): bool {
        try {
            $sql = "UPDATE carga_familiar SET activo = :activo
                    WHERE id_carga = :id_carga 
                    RETURNING id_carga, activo";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id_carga", $this->id_carga);
            $stmt->bindValue(":activo", false);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && isset($result["id_carga"]) && isset($result["activo"])) {
                $this->activo = $result['activo'];
                return true;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Error al eliminar la carga familiar: " . $e->getMessage());
            throw $e;
        }
    }

    // Getters y Setters
    public function getId_carga(): int {
        return $this->id_carga;
    }

    public function setId_carga(int $id_carga): void {
        $this->id_carga = $id_carga;
    }

    public function getId_habitante(): int {
        return $this->id_habitante;
    }

    public function setId_habitante(int $id_habitante): void {
        $this->id_habitante = $id_habitante;
    }

    public function getId_jefe(): int {
        return $this->id_jefe;
    }

    public function setId_jefe(int $id_jefe): void {
        $this->id_jefe = $id_jefe;
    }

    public function getParentesco(): string {
        return $this->parentesco;
    }

    public function setParentesco(string $parentesco): void {
        $this->parentesco = $parentesco;
    }

    public function getActivo(): bool {
        return $this->activo;
    }

    public function setActivo(bool $activo): void {
        $this->activo = $activo;
    }

    public function getFecha_registro(): string {
        return $this->fecha_registro;
    }

    public function setFecha_registro(string $fecha_registro): void {
        $this->fecha_registro = $fecha_registro;
    }

    public function getFecha_actualizacion(): string {
        return $this->fecha_actualizacion;
    }

    public function setFecha_actualizacion(string $fecha_actualizacion): void {
        $this->fecha_actualizacion = $fecha_actualizacion;
    }
}

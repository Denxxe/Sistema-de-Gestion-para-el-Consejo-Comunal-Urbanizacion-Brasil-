<?php
namespace App\models;

use PDO;
use App\Core\Database;
use PDOException;

class LiderCalleModel {
    private PDO $db;

    // Atributos
    private int $id_habitante;
    private string $sector;
    private ?string $zona = null;
    private string $fecha_designacion;
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
            $sql = "INSERT INTO lider_calle (id_habitante, sector, zona, fecha_designacion, fecha_registro, fecha_actualizacion)
                    VALUES (:id_habitante, :sector, :zona, :fecha_designacion, :fecha_registro, :fecha_actualizacion)
                    RETURNING id_habitante";
            
            $stmt = $this->db->prepare($sql);

            $ahora = date('Y-m-d H:i:s');
            $this->fecha_registro = $ahora;
            $this->fecha_actualizacion = $ahora;
        
            $stmt->bindValue(':id_habitante', $this->id_habitante);
            $stmt->bindValue(':sector', $this->sector);
            $stmt->bindValue(':zona', $this->zona);
            $stmt->bindValue(':fecha_designacion', $this->fecha_designacion);
            $stmt->bindValue(':fecha_registro', $this->fecha_registro);
            $stmt->bindValue(':fecha_actualizacion', $this->fecha_actualizacion);
        
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result['id_habitante'])) {
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error al crear el líder de calle: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function eliminar(): bool {
        try {
            $sql = "UPDATE lider_calle SET activo = :activo
                    WHERE id_habitante = :id_habitante 
                    RETURNING id_habitante, activo";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id_habitante", $this->id_habitante);
            $stmt->bindValue(":activo", false);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && isset($result["id_habitante"]) && isset($result["activo"])) {
                $this->activo = $result['activo'];
                return true;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Error al eliminar el líder de calle: " . $e->getMessage());
            throw $e;
        }
    }

    // Getters y Setters
    public function getId_habitante(): int {
        return $this->id_habitante;
    }

    public function setId_habitante(int $id_habitante): void {
        $this->id_habitante = $id_habitante;
    }

    public function getSector(): string {
        return $this->sector;
    }

    public function setSector(string $sector): void {
        $this->sector = $sector;
    }

    public function getZona(): ?string {
        return $this->zona;
    }

    public function setZona(?string $zona): void {
        $this->zona = $zona;
    }

    public function getFecha_designacion(): string {
        return $this->fecha_designacion;
    }

    public function setFecha_designacion(string $fecha_designacion): void {
        $this->fecha_designacion = $fecha_designacion;
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

<?php
namespace App\models;

use PDO;
use App\Core\Database;
use PDOException;

class LiderComunalModel {
    private PDO $db;

    // Atributos
    private int $id_habitante;
    private string $fecha_inicio;
    private ?string $fecha_fin = null;
    private ?string $observaciones = null;
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
            $sql = "INSERT INTO lider_comunal (id_habitante, fecha_inicio, fecha_fin, observaciones, fecha_registro, fecha_actualizacion)
                    VALUES (:id_habitante, :fecha_inicio, :fecha_fin, :observaciones, :fecha_registro, :fecha_actualizacion)
                    RETURNING id_habitante";
            
            $stmt = $this->db->prepare($sql);

            $ahora = date('Y-m-d H:i:s');
            $this->fecha_registro = $ahora;
            $this->fecha_actualizacion = $ahora;
        
            $stmt->bindValue(':id_habitante', $this->id_habitante);
            $stmt->bindValue(':fecha_inicio', $this->fecha_inicio);
            $stmt->bindValue(':fecha_fin', $this->fecha_fin);
            $stmt->bindValue(':observaciones', $this->observaciones);
            $stmt->bindValue(':fecha_registro', $this->fecha_registro);
            $stmt->bindValue(':fecha_actualizacion', $this->fecha_actualizacion);
        
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result['id_habitante'])) {
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error al crear el líder comunal: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function eliminar(): bool {
        try {
            $sql = "UPDATE lider_comunal SET activo = :activo
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
            error_log("Error al eliminar el líder comunal: " . $e->getMessage());
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

    public function getFecha_inicio(): string {
        return $this->fecha_inicio;
    }

    public function setFecha_inicio(string $fecha_inicio): void {
        $this->fecha_inicio = $fecha_inicio;
    }

    public function getFecha_fin(): ?string {
        return $this->fecha_fin;
    }

    public function setFecha_fin(?string $fecha_fin): void {
        $this->fecha_fin = $fecha_fin;
    }

    public function getObservaciones(): ?string {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): void {
        $this->observaciones = $observaciones;
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

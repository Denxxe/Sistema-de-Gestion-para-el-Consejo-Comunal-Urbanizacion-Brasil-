<?php
namespace App\models;

use PDO;
use App\Core\Database;
use PDOException;

class HabitanteModel {
    private PDO $db;

    // Atributos
    private int $id_habitante;
    private int $id_persona;
    private int $id_vivienda;
    private ?string $fecha_ingreso;
    private ?string $condicion;
    private bool $activo;
    private string $fecha_registro;
    private string $fecha_actualizacion;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function crear(): bool {
        try {
            $sql = "INSERT INTO habitante (
                id_persona,
                fecha_ingreso,
                condicion,
                activo,
                fecha_registro
            )
            VALUES (
                :id_persona,
                :fecha_ingreso,
                :condicion,
                :activo,
                :fecha_registro
            )
            RETURNING id_habitante";
            
            $stmt = $this->db->prepare($sql);

            $ahora = date('Y-m-d H:i:s');
            $this->fecha_registro = $ahora;
            $this->fecha_ingreso = $ahora;
            $this->condicion = 'ACTIVO';
            $this->activo = true;
        
            $stmt->bindValue(':id_persona', $this->id_persona);
            $stmt->bindValue(':fecha_ingreso', $this->fecha_ingreso);
            $stmt->bindValue(':condicion', $this->condicion);
            $stmt->bindValue(':activo', $this->activo);
            $stmt->bindValue(':fecha_registro', $this->fecha_registro);
        
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result['id_habitante'])) {
                $this->id_habitante = $result['id_habitante'];
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error al crear habitante: " . $e->getMessage());
            throw $e;
        }
    }

    public function actualizar(): bool {
        try {
            $sql = "UPDATE habitante SET 
                    id_persona = :id_persona,
                    id_vivienda = :id_vivienda
                    WHERE id_habitante = :id_habitante AND activo = true
                    RETURNING id_habitante";
            
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindValue(':id_persona', $this->id_persona);
            $stmt->bindValue(':id_vivienda', $this->id_vivienda);
            $stmt->bindValue(':id_habitante', $this->id_habitante);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result !== false;
        } catch (PDOException $e) {
            error_log("Error al actualizar habitante: " . $e->getMessage());
            throw $e;
        }
    }

    public function eliminar(): bool {
        try {
            $sql = "UPDATE habitante SET activo = false
                    WHERE id_habitante = :id_habitante AND activo = true
                    RETURNING id_habitante";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id_habitante', $this->id_habitante);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result !== false;
        } catch (PDOException $e) {
            error_log("Error al eliminar habitante: " . $e->getMessage());
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

    public function getId_persona(): int {
        return $this->id_persona;
    }

    public function setId_persona(int $id_persona): void {
        $this->id_persona = $id_persona;
    }

    public function getId_vivienda(): int {
        return $this->id_vivienda;
    }

    public function setId_vivienda(int $id_vivienda): void {
        $this->id_vivienda = $id_vivienda;
    }

    public function getFecha_ingreso(): ?string {
        return $this->fecha_ingreso;
    }

    public function setFecha_ingreso(?string $fecha_ingreso): void {
        $this->fecha_ingreso = $fecha_ingreso;
    }

    public function getCondicion(): ?string {
        return $this->condicion;
    }

    public function setCondicion(?string $condicion): void {
        $this->condicion = $condicion;
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
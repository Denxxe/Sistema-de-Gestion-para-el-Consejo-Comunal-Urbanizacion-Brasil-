<?php
namespace App\models;

use PDO;
use App\Core\Database;
use PDOException;

class HabitanteViviendaModel {
    private PDO $db;

    // Atributos
    private int $id_habitante;
    private int $id_vivienda;
    private bool $es_jefe_familia;
    private string $fecha_inicio;
    private ?string $fecha_salida = null;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Métodos CRUD
    public function crear(): bool {
        try {
            $sql = "INSERT INTO habitante_vivienda (id_habitante, id_vivienda, es_jefe_familia, fecha_inicio, fecha_salida)
                    VALUES (:id_habitante, :id_vivienda, :es_jefe_familia, :fecha_inicio, :fecha_salida)
                    RETURNING id_habitante, id_vivienda";
            
            $stmt = $this->db->prepare($sql);
        
            $stmt->bindValue(':id_habitante', $this->id_habitante);
            $stmt->bindValue(':id_vivienda', $this->id_vivienda);
            $stmt->bindValue(':es_jefe_familia', $this->es_jefe_familia);
            $stmt->bindValue(':fecha_inicio', $this->fecha_inicio);
            $stmt->bindValue(':fecha_salida', $this->fecha_salida);
        
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result['id_habitante']) && isset($result['id_vivienda'])) {
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error al crear la relación habitante-vivienda: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function eliminar(): bool {
        try {
            $sql = "DELETE FROM habitante_vivienda 
                    WHERE id_habitante = :id_habitante AND id_vivienda = :id_vivienda 
                    RETURNING id_habitante, id_vivienda";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id_habitante", $this->id_habitante);
            $stmt->bindValue(":id_vivienda", $this->id_vivienda);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && isset($result["id_habitante"]) && isset($result["id_vivienda"])) {
                return true;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Error al eliminar la relación habitante-vivienda: " . $e->getMessage());
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

    public function getId_vivienda(): int {
        return $this->id_vivienda;
    }

    public function setId_vivienda(int $id_vivienda): void {
        $this->id_vivienda = $id_vivienda;
    }

    public function getEs_jefe_familia(): bool {
        return $this->es_jefe_familia;
    }

    public function setEs_jefe_familia(bool $es_jefe_familia): void {
        $this->es_jefe_familia = $es_jefe_familia;
    }

    public function getFecha_inicio(): string {
        return $this->fecha_inicio;
    }

    public function setFecha_inicio(string $fecha_inicio): void {
        $this->fecha_inicio = $fecha_inicio;
    }

    public function getFecha_salida(): ?string {
        return $this->fecha_salida;
    }

    public function setFecha_salida(?string $fecha_salida): void {
        $this->fecha_salida = $fecha_salida;
    }
}

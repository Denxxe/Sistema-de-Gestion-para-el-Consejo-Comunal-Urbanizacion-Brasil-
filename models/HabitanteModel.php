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
    private ?string $fecha_ingreso;
    private ?string $condicion = 'ACTIVO';
    private bool $activo = true;
    private string $fecha_registro;
    private string $fecha_actualizacion;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Métodos CRUD
    public function listar(array $filtros = []): array {
        try {
            $sql = "SELECT h.id_habitante,
               h.fecha_ingreso,
               h.condicion,
               p.id_persona,
               p.nombres,
               p.apellidos,
               p.cedula,
               p.sexo,
               p.estado,
               p.fecha_nacimiento
            FROM habitante h
            INNER JOIN persona p ON p.id_persona = h.id_persona
            WHERE h.activo = true";
            $params = [];

            // Si hay filtros, los agregamos a la consulta
            if (!empty($filtros)) {
                $condiciones = [];
                foreach ($filtros as $campo => $valor) {
                    if (property_exists($this, $campo)) {
                        $condiciones[] = "$campo = :$campo";
                        $params[":$campo"] = $valor;
                    }
                }
                if (!empty($condiciones)) {
                    $sql .= " AND " . implode(" AND ", $condiciones);
                }
            }

            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar habitantes: " . $e->getMessage());
            throw $e;
        }
    }

    public function obtenerPorId(int $id): ?array {
        try {
            $sql = "SELECT h.id_habitante,
               h.fecha_ingreso,
               h.condicion,
               p.id_persona,
               p.nombres,
               p.apellidos,
               p.cedula,
               p.sexo,
               p.estado,
               p.fecha_nacimiento
            FROM habitante h
            INNER JOIN persona p ON p.id_persona = h.id_persona
            WHERE id_habitante = :id AND h.activo = true";
     
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener habitante: " . $e->getMessage());
            throw $e;
        }
    }

    public function crear(): array {
        try {
            $sql = "INSERT INTO habitante (
                id_persona,
                fecha_ingreso,
                condicion,
                fecha_registro,
                fecha_actualizacion,
                activo)
                VALUES (
                :id_persona,
                :fecha_ingreso,
                :condicion,
                :fecha_registro,
                :fecha_actualizacion,
                :activo)
                RETURNING id_habitante";
            
            $stmt = $this->db->prepare($sql);

            $ahora = date('Y-m-d H:i:s');
            $this->fecha_registro = $ahora;
            $this->fecha_actualizacion = $ahora;
            $this->activo = true;
            
            $stmt->bindValue(':id_persona', $this->id_persona);
            $stmt->bindValue(':fecha_ingreso', $this->fecha_ingreso);
            $stmt->bindValue(':condicion', $this->condicion);
            $stmt->bindValue(':fecha_registro', $this->fecha_registro);
            $stmt->bindValue(':fecha_actualizacion', $this->fecha_actualizacion);
            $stmt->bindValue(':activo', $this->activo);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result['id_habitante'])) {
                $this->id_habitante = $result['id_habitante'];
                
                // Ahora obtenemos los datos completos con el JOIN
                return $this->obtenerPorId($this->id_habitante);
            }
            
            return [];
        } catch (PDOException $e) {
            error_log("Error al crear habitante: " . $e->getMessage());
            throw $e;
        }
    }

    public function actualizar(): bool {
        try {
            $sql = "UPDATE habitante SET 
                    id_persona = :id_persona,
                    fecha_ingreso = :fecha_ingreso,
                    condicion = :condicion,
                    WHERE id_habitante = :id_habitante AND activo = true
                    RETURNING id_habitante";
            
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindValue(':id_persona', $this->id_persona);
            $stmt->bindValue(':fecha_ingreso', $this->fecha_ingreso);
            $stmt->bindValue(':condicion', $this->condicion);
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

// Método para contar registros
public function contar(array $filtros = []): int {
    try {
        $sql = 'SELECT COUNT(*) AS total FROM habitante WHERE activo = true';
        $params = [];

        // Aplicar filtros si se proporcionan
        if (!empty($filtros)) {
            $condiciones = [];
            foreach ($filtros as $campo => $valor) {
                if (property_exists($this, $campo)) {
                    $condiciones[] = "$campo = :$campo";
                    $params[":$campo"] = $valor;
                }
            }
            if (!empty($condiciones)) {
                $sql .= ' AND ' . implode(' AND ', $condiciones);
            }
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($resultado['total'] ?? 0);
    } catch (PDOException $e) {
        error_log('Error al contar habitante: ' . $e->getMessage());
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
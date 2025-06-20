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
    /**
     * Lista relaciones habitante-vivienda activas. Incluye datos de persona y datos básicos de vivienda.
     */
    public function listar(array $filtros = []): array {
        try {
            $sql = "SELECT hv.id_habitante,
                           hv.id_vivienda,
                           hv.es_jefe_familia,
                           hv.fecha_inicio,
                           hv.fecha_salida,
                           p.cedula,
                           p.nombres,
                           p.apellidos,
                           v.direccion
                    FROM habitante_vivienda hv
                    INNER JOIN habitante h ON h.id_habitante = hv.id_habitante
                    INNER JOIN persona p ON p.id_persona = h.id_persona
                    INNER JOIN vivienda v ON v.id_vivienda = hv.id_vivienda";

            $params = [];
            if ($filtros) {
                $condiciones = [];
                foreach ($filtros as $campo => $valor) {
                    if (property_exists($this, $campo)) {
                        $condiciones[] = "hv.$campo = :$campo";
                        $params[":$campo"] = $valor;
                    }
                }
                if ($condiciones) {
                    $sql .= " WHERE " . implode(" AND ", $condiciones);
                }
            }

            $stmt = $this->db->prepare($sql);
            foreach ($params as $k => $v) {
                $stmt->bindValue($k, $v);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar habitante_vivienda: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtiene la relación por IDs.
     */
    public function obtenerPorId(int $idHabitante, int $idVivienda): ?array {
        try {
            $sql = "SELECT hv.id_habitante,
                           hv.id_vivienda,
                           hv.es_jefe_familia,
                           hv.fecha_inicio,
                           hv.fecha_salida,
                           p.cedula,
                           p.nombres,
                           p.apellidos,
                           v.direccion
                    FROM habitante_vivienda hv
                    INNER JOIN habitante h ON h.id_habitante = hv.id_habitante
                    INNER JOIN persona p ON p.id_persona = h.id_persona
                    INNER JOIN vivienda v ON v.id_vivienda = hv.id_vivienda
                    WHERE hv.id_habitante = :idh AND hv.id_vivienda = :idv";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':idh', $idHabitante);
            $stmt->bindValue(':idv', $idVivienda);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener habitante_vivienda: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Actualiza la relación (por ejemplo, marcar salida o cambiar jefe de familia).
     */
    public function actualizar(): bool {
        try {
            $sql = "UPDATE habitante_vivienda SET
                        es_jefe_familia = :es_jefe_familia,
                        fecha_inicio    = :fecha_inicio,
                        fecha_salida    = :fecha_salida
                    WHERE id_habitante = :id_habitante AND id_vivienda = :id_vivienda
                    RETURNING id_habitante";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':es_jefe_familia', $this->es_jefe_familia);
            $stmt->bindValue(':fecha_inicio', $this->fecha_inicio);
            $stmt->bindValue(':fecha_salida', $this->fecha_salida);
            $stmt->bindValue(':id_habitante', $this->id_habitante);
            $stmt->bindValue(':id_vivienda', $this->id_vivienda);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result !== false;
        } catch (PDOException $e) {
            error_log("Error al actualizar habitante_vivienda: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cuenta relaciones, usando filtros opcionales.
     */
    public function contar(array $filtros = []): int {
        try {
            $sql = "SELECT COUNT(*) AS total FROM habitante_vivienda hv";
            $params = [];

            if ($filtros) {
                $condiciones = [];
                foreach ($filtros as $campo => $valor) {
                    if (property_exists($this, $campo)) {
                        $condiciones[] = "hv.$campo = :$campo";
                        $params[":$campo"] = $valor;
                    }
                }
                if ($condiciones) {
                    $sql .= " WHERE " . implode(" AND ", $condiciones);
                }
            }

            $stmt = $this->db->prepare($sql);
            foreach ($params as $k => $v) {
                $stmt->bindValue($k, $v);
            }
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int) $result['total'] : 0;
        } catch (PDOException $e) {
            error_log("Error al contar habitante_vivienda: " . $e->getMessage());
            throw $e;
        }
    }

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

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
    /**
     * Lista líderes de calle activos con datos de persona.
     */
    public function listar(array $filtros = []): array {
        try {
            $sql = "SELECT lc.id_habitante,
                           lc.sector,
                           lc.zona,
                           lc.fecha_designacion,
                           h.id_habitante,
                           p.cedula,
                           p.nombres,
                           p.apellidos
                    FROM lider_calle lc
                    INNER JOIN habitante h ON h.id_habitante = lc.id_habitante
                    INNER JOIN persona p ON p.id_persona = h.id_persona
                    WHERE lc.activo = true";

            $params = [];
            if ($filtros) {
                $condiciones = [];
                foreach ($filtros as $campo => $valor) {
                    if (property_exists($this, $campo)) {
                        $condiciones[] = "lc.$campo = :$campo";
                        $params[":$campo"] = $valor;
                    }
                }
                if ($condiciones) {
                    $sql .= " AND " . implode(" AND ", $condiciones);
                }
            }

            $stmt = $this->db->prepare($sql);
            foreach ($params as $k => $v) {
                $stmt->bindValue($k, $v);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar líderes de calle: " . $e->getMessage());
            throw $e;
        }
    }

    public function obtenerPorId(int $idHabitante): ?array {
        try {
            $sql = "SELECT lc.id_habitante,
                           lc.sector,
                           lc.zona,
                           lc.fecha_designacion,
                           p.cedula,
                           p.nombres,
                           p.apellidos
                    FROM lider_calle lc
                    INNER JOIN habitante h ON h.id_habitante = lc.id_habitante
                    INNER JOIN persona p ON p.id_persona = h.id_persona
                    WHERE lc.id_habitante = :id AND lc.activo = true";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $idHabitante);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener líder de calle: " . $e->getMessage());
            throw $e;
        }
    }

    public function actualizar(): bool {
        try {
            $sql = "UPDATE lider_calle SET
                        sector = :sector,
                        zona   = :zona,
                        fecha_designacion = :fecha_designacion,
                        fecha_actualizacion = :fecha_actualizacion
                    WHERE id_habitante = :id_habitante AND activo = true
                    RETURNING id_habitante";

            $stmt = $this->db->prepare($sql);
            $this->fecha_actualizacion = date('Y-m-d H:i:s');

            $stmt->bindValue(':sector', $this->sector);
            $stmt->bindValue(':zona', $this->zona);
            $stmt->bindValue(':fecha_designacion', $this->fecha_designacion);
            $stmt->bindValue(':fecha_actualizacion', $this->fecha_actualizacion);
            $stmt->bindValue(':id_habitante', $this->id_habitante);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result !== false;
        } catch (PDOException $e) {
            error_log("Error al actualizar líder de calle: " . $e->getMessage());
            throw $e;
        }
    }

    public function contar(array $filtros = []): int {
        try {
            $sql = "SELECT COUNT(*) AS total FROM lider_calle lc WHERE lc.activo = true";
            $params = [];
            if ($filtros) {
                $condiciones = [];
                foreach ($filtros as $campo => $valor) {
                    if (property_exists($this, $campo)) {
                        $condiciones[] = "lc.$campo = :$campo";
                        $params[":$campo"] = $valor;
                    }
                }
                if ($condiciones) {
                    $sql .= " AND " . implode(" AND ", $condiciones);
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
            error_log("Error al contar líderes de calle: " . $e->getMessage());
            throw $e;
        }
    }

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

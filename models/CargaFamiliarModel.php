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
    /**
     * Lista las cargas familiares activas. Incluye información básica del habitante (dependiente)
     * y del jefe de familia, uniendo tablas habitante y persona.
     */
    public function listar(array $filtros = []): array {
        try {
            $sql = "SELECT cf.id_carga,
                           cf.parentesco,
                           cf.fecha_registro,
                           h.id_habitante,
                           ph.cedula  AS cedula_dependiente,
                           ph.nombres AS nombres_dependiente,
                           ph.apellidos AS apellidos_dependiente,
                           j.id_habitante AS id_jefe,
                           pj.cedula  AS cedula_jefe,
                           pj.nombres AS nombres_jefe,
                           pj.apellidos AS apellidos_jefe
                    FROM carga_familiar cf
                    INNER JOIN habitante h ON h.id_habitante = cf.id_habitante
                    INNER JOIN persona ph ON ph.id_persona = h.id_persona
                    INNER JOIN habitante j ON j.id_habitante = cf.id_jefe
                    INNER JOIN persona pj ON pj.id_persona = j.id_persona
                    WHERE cf.activo = true";

            $params = [];
            if ($filtros) {
                $condiciones = [];
                foreach ($filtros as $campo => $valor) {
                    if (property_exists($this, $campo)) {
                        $condiciones[] = "cf.$campo = :$campo";
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
            error_log("Error al listar cargas familiares: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtiene una carga familiar por su ID con los mismos JOINs de listar().
     */
    public function obtenerPorId(int $id): ?array {
        try {
            $sql = "SELECT cf.id_carga,
                           cf.parentesco,
                           cf.fecha_registro,
                           h.id_habitante,
                           ph.cedula  AS cedula_dependiente,
                           ph.nombres AS nombres_dependiente,
                           ph.apellidos AS apellidos_dependiente,
                           j.id_habitante AS id_jefe,
                           pj.cedula  AS cedula_jefe,
                           pj.nombres AS nombres_jefe,
                           pj.apellidos AS apellidos_jefe
                    FROM carga_familiar cf
                    INNER JOIN habitante h ON h.id_habitante = cf.id_habitante
                    INNER JOIN persona ph ON ph.id_persona = h.id_persona
                    INNER JOIN habitante j ON j.id_habitante = cf.id_jefe
                    INNER JOIN persona pj ON pj.id_persona = j.id_persona
                    WHERE cf.id_carga = :id AND cf.activo = true";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener carga familiar: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Actualiza los datos de la carga familiar.
     */
    public function actualizar(): bool {
        try {
            $sql = "UPDATE carga_familiar SET
                        id_habitante = :id_habitante,
                        id_jefe      = :id_jefe,
                        parentesco   = :parentesco,
                        fecha_actualizacion = :fecha_actualizacion
                    WHERE id_carga = :id_carga AND activo = true
                    RETURNING id_carga";

            $stmt = $this->db->prepare($sql);

            $this->fecha_actualizacion = date('Y-m-d H:i:s');

            $stmt->bindValue(':id_habitante', $this->id_habitante);
            $stmt->bindValue(':id_jefe', $this->id_jefe);
            $stmt->bindValue(':parentesco', $this->parentesco);
            $stmt->bindValue(':fecha_actualizacion', $this->fecha_actualizacion);
            $stmt->bindValue(':id_carga', $this->id_carga);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result !== false;
        } catch (PDOException $e) {
            error_log("Error al actualizar carga familiar: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cuenta las cargas familiares activas según filtros opcionales.
     */
    public function contar(array $filtros = []): int {
        try {
            $sql = "SELECT COUNT(*) AS total FROM carga_familiar cf WHERE cf.activo = true";
            $params = [];

            if ($filtros) {
                $condiciones = [];
                foreach ($filtros as $campo => $valor) {
                    if (property_exists($this, $campo)) {
                        $condiciones[] = "cf.$campo = :$campo";
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
            error_log("Error al contar cargas familiares: " . $e->getMessage());
            throw $e;
        }
    }

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

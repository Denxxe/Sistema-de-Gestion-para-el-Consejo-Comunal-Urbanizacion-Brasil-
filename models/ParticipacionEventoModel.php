<?php
namespace App\models;

use PDO;
use App\Core\Database;
use PDOException;

class ParticipacionEventoModel {
    private PDO $db;

    // Atributos
    private int $id_participacion;
    private int $id_evento;
    private int $id_usuario;
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
     * Lista participaciones activas con nombre de usuario (persona) y título de evento.
     */
    public function listar(array $filtros = []): array {
        try {
            $sql = "SELECT pe.id_participacion,
                           pe.fecha_registro,
                           e.id_evento,
                           e.titulo AS evento,
                           u.id_usuario,
                           p.cedula,
                           p.nombres,
                           p.apellidos
                    FROM participacion_evento pe
                    INNER JOIN evento e ON e.id_evento = pe.id_evento
                    INNER JOIN usuario u ON u.id_usuario = pe.id_usuario
                    INNER JOIN persona p ON p.id_persona = u.id_persona
                    WHERE pe.activo = true";

            $params = [];
            if ($filtros) {
                $condiciones = [];
                foreach ($filtros as $campo => $valor) {
                    if (property_exists($this, $campo)) {
                        $condiciones[] = "pe.$campo = :$campo";
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
            error_log("Error al listar participación evento: " . $e->getMessage());
            throw $e;
        }
    }

    public function obtenerPorId(int $id): ?array {
        try {
            $sql = "SELECT pe.id_participacion,
                           pe.fecha_registro,
                           e.id_evento,
                           e.titulo AS evento,
                           u.id_usuario,
                           p.cedula,
                           p.nombres,
                           p.apellidos
                    FROM participacion_evento pe
                    INNER JOIN evento e ON e.id_evento = pe.id_evento
                    INNER JOIN usuario u ON u.id_usuario = pe.id_usuario
                    INNER JOIN persona p ON p.id_persona = u.id_persona
                    WHERE pe.id_participacion = :id AND pe.activo = true";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener participación evento: " . $e->getMessage());
            throw $e;
        }
    }

    public function actualizar(): bool {
        try {
            $sql = "UPDATE participacion_evento SET
                        id_evento = :id_evento,
                        id_usuario = :id_usuario,
                        fecha_actualizacion = :fecha_actualizacion
                    WHERE id_participacion = :id_participacion AND activo = true
                    RETURNING id_participacion";

            $stmt = $this->db->prepare($sql);
            $this->fecha_actualizacion = date('Y-m-d H:i:s');

            $stmt->bindValue(':id_evento', $this->id_evento);
            $stmt->bindValue(':id_usuario', $this->id_usuario);
            $stmt->bindValue(':fecha_actualizacion', $this->fecha_actualizacion);
            $stmt->bindValue(':id_participacion', $this->id_participacion);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result !== false;
        } catch (PDOException $e) {
            error_log("Error al actualizar participación evento: " . $e->getMessage());
            throw $e;
        }
    }

    public function contar(array $filtros = []): int {
        try {
            $sql = "SELECT COUNT(*) AS total FROM participacion_evento pe WHERE pe.activo = true";
            $params = [];
            if ($filtros) {
                $condiciones = [];
                foreach ($filtros as $campo => $valor) {
                    if (property_exists($this, $campo)) {
                        $condiciones[] = "pe.$campo = :$campo";
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
            error_log("Error al contar participación evento: " . $e->getMessage());
            throw $e;
        }
    }

    public function crear(): bool {
        try {
            $sql = "INSERT INTO participacion_evento (id_evento, id_usuario, fecha_registro, fecha_actualizacion)
                    VALUES (:id_evento, :id_usuario, :fecha_registro, :fecha_actualizacion)
                    RETURNING id_participacion";
            
            $stmt = $this->db->prepare($sql);

            $ahora = date('Y-m-d H:i:s');
            $this->fecha_registro = $ahora;
            $this->fecha_actualizacion = $ahora;
        
            $stmt->bindValue(':id_evento', $this->id_evento);
            $stmt->bindValue(':id_usuario', $this->id_usuario);
            $stmt->bindValue(':fecha_registro', $this->fecha_registro);
            $stmt->bindValue(':fecha_actualizacion', $this->fecha_actualizacion);
        
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result['id_participacion'])) {
                $this->id_participacion = $result['id_participacion'];
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error al crear la participación en evento: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function eliminar(): bool {
        try {
            $sql = "UPDATE participacion_evento SET activo = :activo
                    WHERE id_participacion = :id_participacion 
                    RETURNING id_participacion, activo";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id_participacion", $this->id_participacion);
            $stmt->bindValue(":activo", false);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && isset($result["id_participacion"]) && isset($result["activo"])) {
                $this->activo = $result['activo'];
                return true;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Error al eliminar la participación en evento: " . $e->getMessage());
            throw $e;
        }
    }

    // Getters y Setters
    public function getId_participacion(): int {
        return $this->id_participacion;
    }

    public function setId_participacion(int $id_participacion): void {
        $this->id_participacion = $id_participacion;
    }

    public function getId_evento(): int {
        return $this->id_evento;
    }

    public function setId_evento(int $id_evento): void {
        $this->id_evento = $id_evento;
    }

    public function getId_usuario(): int {
        return $this->id_usuario;
    }

    public function setId_usuario(int $id_usuario): void {
        $this->id_usuario = $id_usuario;
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

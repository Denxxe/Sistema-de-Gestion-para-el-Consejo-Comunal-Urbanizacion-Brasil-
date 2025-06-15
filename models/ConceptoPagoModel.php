<?php
namespace App\models;

use PDO;
use App\Core\Database;
use PDOException;

class ConceptoPagoModel {
    private PDO $db;

    // Atributos
    private int $id_concepto;
    private string $nombre;
    private ?string $descripcion = null;
    private float $monto;
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
            $sql = "SELECT * FROM concepto_pago WHERE activo = true";
            $params = [];

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
            error_log("Error al listar conceptos de pago: " . $e->getMessage());
            throw $e;
        }
    }

    public function obtenerPorId(int $id): ?array {
        try {
            $sql = "SELECT * FROM concepto_pago WHERE id_concepto = :id AND activo = true";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener concepto de pago: " . $e->getMessage());
            throw $e;
        }
    }

    public function crear(): bool {
        try {
            $sql = "INSERT INTO concepto_pago (nombre, descripcion, monto, fecha_registro)
                    VALUES (:nombre, :descripcion, :monto, :fecha_registro)
                    RETURNING id_concepto";
            
            $stmt = $this->db->prepare($sql);

            $ahora = date('Y-m-d H:i:s');
            $this->fecha_registro = $ahora;
        
            $stmt->bindValue(':nombre', $this->nombre);
            $stmt->bindValue(':descripcion', $this->descripcion);
            $stmt->bindValue(':monto', $this->monto);
            $stmt->bindValue(':fecha_registro', $this->fecha_registro);
        
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result['id_concepto'])) {
                $this->id_concepto = $result['id_concepto'];
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error al crear concepto de pago: " . $e->getMessage());
            throw $e;
        }
    }

    public function actualizar(): bool {
        try {
            $sql = "UPDATE concepto_pago SET 
                    nombre = :nombre,
                    descripcion = :descripcion,
                    monto = :monto
                    WHERE id_concepto = :id_concepto AND activo = true
                    RETURNING id_concepto";
            
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindValue(':nombre', $this->nombre);
            $stmt->bindValue(':descripcion', $this->descripcion);
            $stmt->bindValue(':monto', $this->monto);
            $stmt->bindValue(':id_concepto', $this->id_concepto);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result !== false;
        } catch (PDOException $e) {
            error_log("Error al actualizar concepto de pago: " . $e->getMessage());
            throw $e;
        }
    }

    public function eliminar(): bool {
        try {
            $sql = "UPDATE concepto_pago SET activo = false
                    WHERE id_concepto = :id_concepto AND activo = true
                    RETURNING id_concepto";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id_concepto', $this->id_concepto);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result !== false;
        } catch (PDOException $e) {
            error_log("Error al eliminar concepto de pago: " . $e->getMessage());
            throw $e;
        }
    }

    public function contar(array $filtros = []): int {
        try {
            $sql = "SELECT COUNT(*) AS total FROM concepto_pago WHERE activo = true";
            $params = [];

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
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int) $result['total'] : 0;
        } catch (PDOException $e) {
            error_log("Error al contar conceptos de pago: " . $e->getMessage());
            throw $e;
        }
    }

    // Getters y Setters
    public function getId_concepto(): int {
        return $this->id_concepto;
    }

    public function setId_concepto(int $id_concepto): void {
        $this->id_concepto = $id_concepto;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function getDescripcion(): ?string {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): void {
        $this->descripcion = $descripcion;
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

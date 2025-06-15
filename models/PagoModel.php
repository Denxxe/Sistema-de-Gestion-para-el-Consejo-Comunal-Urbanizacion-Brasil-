<?php
namespace App\models;

use PDO;
use App\Core\Database;
use PDOException;

class PagoModel {
    private PDO $db;

    // Atributos
    private int $id_pago;
    private int $id_usuario;
    private int $id_concepto;
    private float $monto;
    private string $fecha_pago;
    private string $estado_pago;
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
     * Lista pagos activos, uniendo información del usuario (con persona) y del concepto de pago.
     * Permite filtros opcionales por cualquier campo existente en la tabla pago.
     */
    public function listar(array $filtros = []): array {
        try {
            $sql = "SELECT p.id_pago,
                           p.monto,
                           p.fecha_pago,
                           p.estado_pago,
                           p.fecha_registro,
                           u.id_usuario,
                           per.cedula,
                           per.nombres,
                           per.apellidos,
                           c.id_concepto,
                           c.nombre AS concepto
                    FROM pago p
                    INNER JOIN usuario u ON u.id_usuario = p.id_usuario
                    INNER JOIN persona per ON per.id_persona = u.id_persona
                    INNER JOIN concepto_pago c ON c.id_concepto = p.id_concepto
                    WHERE p.activo = true";

            $params = [];
            if (!empty($filtros)) {
                $condiciones = [];
                foreach ($filtros as $campo => $valor) {
                    // Solo aplicar filtros que existan como propiedad en PagoModel
                    if (property_exists($this, $campo)) {
                        $condiciones[] = "p.$campo = :$campo";
                        $params[":$campo"] = $valor;
                    }
                }
                if ($condiciones) {
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
            error_log("Error al listar pagos: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtiene un pago por su ID con los mismos JOINs que listar().
     */
    public function obtenerPorId(int $id): ?array {
        try {
            $sql = "SELECT p.id_pago,
                           p.monto,
                           p.fecha_pago,
                           p.estado_pago,
                           p.fecha_registro,
                           u.id_usuario,
                           per.cedula,
                           per.nombres,
                           per.apellidos,
                           c.id_concepto,
                           c.nombre AS concepto
                    FROM pago p
                    INNER JOIN usuario u ON u.id_usuario = p.id_usuario
                    INNER JOIN persona per ON per.id_persona = u.id_persona
                    INNER JOIN concepto_pago c ON c.id_concepto = p.id_concepto
                    WHERE p.id_pago = :id AND p.activo = true";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener pago: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Actualiza un pago existente.
     */
    public function actualizar(): bool {
        try {
            $sql = "UPDATE pago SET
                        id_usuario   = :id_usuario,
                        id_concepto  = :id_concepto,
                        monto        = :monto,
                        fecha_pago   = :fecha_pago,
                        estado_pago  = :estado_pago,
                        fecha_actualizacion = :fecha_actualizacion
                    WHERE id_pago = :id_pago AND activo = true
                    RETURNING id_pago";

            $stmt = $this->db->prepare($sql);

            $this->fecha_actualizacion = date('Y-m-d H:i:s');

            $stmt->bindValue(':id_usuario', $this->id_usuario);
            $stmt->bindValue(':id_concepto', $this->id_concepto);
            $stmt->bindValue(':monto', $this->monto);
            $stmt->bindValue(':fecha_pago', $this->fecha_pago);
            $stmt->bindValue(':estado_pago', $this->estado_pago);
            $stmt->bindValue(':fecha_actualizacion', $this->fecha_actualizacion);
            $stmt->bindValue(':id_pago', $this->id_pago);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result !== false;
        } catch (PDOException $e) {
            error_log("Error al actualizar pago: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cuenta pagos activos, con filtros opcionales.
     */
    public function contar(array $filtros = []): int {
        try {
            $sql = "SELECT COUNT(*) AS total FROM pago p WHERE p.activo = true";
            $params = [];

            if ($filtros) {
                $condiciones = [];
                foreach ($filtros as $campo => $valor) {
                    if (property_exists($this, $campo)) {
                        $condiciones[] = "p.$campo = :$campo";
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
            error_log("Error al contar pagos: " . $e->getMessage());
            throw $e;
        }
    }

    public function crear(): bool {
        try {
            $sql = "INSERT INTO pago (id_usuario, id_concepto, monto, fecha_pago, estado_pago, fecha_registro)
                    VALUES (:id_usuario, :id_concepto, :monto, :fecha_pago, :estado_pago, :fecha_registro)
                    RETURNING id_pago";
            
            $stmt = $this->db->prepare($sql);

            $ahora = date('Y-m-d H:i:s');
            $this->fecha_registro = $ahora;
        
            $stmt->bindValue(':id_usuario', $this->id_usuario);
            $stmt->bindValue(':id_concepto', $this->id_concepto);
            $stmt->bindValue(':monto', $this->monto);
            $stmt->bindValue(':fecha_pago', $this->fecha_pago);
            $stmt->bindValue(':estado_pago', $this->estado_pago);
            $stmt->bindValue(':fecha_registro', $this->fecha_registro);
        
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result['id_pago'])) {
                $this->id_pago = $result['id_pago'];
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error al crear el pago: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function eliminar(): bool {
        try {
            $sql = "UPDATE pago SET activo = :activo
                    WHERE id_pago = :id_pago 
                    RETURNING id_pago, activo";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id_pago", $this->id_pago);
            $stmt->bindValue(":activo", false);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && isset($result["id_pago"]) && isset($result["activo"])) {
                $this->activo = $result['activo'];
                return true;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Error al eliminar el pago: " . $e->getMessage());
            throw $e;
        }
    }

    // Getters y Setters
    public function getId_pago(): int {
        return $this->id_pago;
    }

    public function setId_pago(int $id_pago): void {
        $this->id_pago = $id_pago;
    }

    public function getId_usuario(): int {
        return $this->id_usuario;
    }

    public function setId_usuario(int $id_usuario): void {
        $this->id_usuario = $id_usuario;
    }

    public function getId_concepto(): int {
        return $this->id_concepto;
    }

    public function setId_concepto(int $id_concepto): void {
        $this->id_concepto = $id_concepto;
    }

    public function getMonto(): float {
        return $this->monto;
    }

    public function setMonto(float $monto): void {
        $this->monto = $monto;
    }

    public function getFecha_pago(): string {
        return $this->fecha_pago;
    }

    public function setFecha_pago(string $fecha_pago): void {
        $this->fecha_pago = $fecha_pago;
    }

    public function getEstado_pago(): string {
        return $this->estado_pago;
    }

    public function setEstado_pago(string $estado_pago): void {
        $this->estado_pago = $estado_pago;
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

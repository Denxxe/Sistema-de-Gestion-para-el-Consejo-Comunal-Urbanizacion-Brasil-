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

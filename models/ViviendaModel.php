<?php
namespace App\models;

use PDO;
use App\Core\Database;
use PDOException;

class ViviendaModel {
    private PDO $db;

    // Atributos
    private int $id_vivienda;
    private string $direccion;
    private string $numero;
    private string $tipo;
    private string $sector;
    private ?string $estado = null;
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
            $sql = "SELECT * FROM vivienda WHERE activo = true";
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
            error_log("Error al listar viviendas: " . $e->getMessage());
            throw $e;
        }
    }

    public function obtenerPorId(int $id): ?array {
        try {
            $sql = "SELECT * FROM vivienda WHERE id_vivienda = :id AND activo = true";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener vivienda: " . $e->getMessage());
            throw $e;
        }
    }

    public function crear(): bool {
        try {
            $sql = "INSERT INTO vivienda (
                direccion,
                numero, 
                tipo, 
                sector, 
                estado, 
                fecha_registro, 
                fecha_actualizacion)
                VALUES (
                :direccion, 
                :numero, 
                :tipo, 
                :sector, 
                :estado, 
                :fecha_registro, 
                :fecha_actualizacion)
                RETURNING id_vivienda";
            
            $stmt = $this->db->prepare($sql);

            $ahora = date('Y-m-d H:i:s');
            $this->fecha_registro = $ahora;
            $this->fecha_actualizacion = $ahora;
        
            $stmt->bindValue(':direccion', $this->direccion);
            $stmt->bindValue(':numero', $this->numero);
            $stmt->bindValue(':tipo', $this->tipo);
            $stmt->bindValue(':sector', $this->sector);
            $stmt->bindValue(':estado', $this->estado);
            $stmt->bindValue(':fecha_registro', $this->fecha_registro);
            $stmt->bindValue(':fecha_actualizacion', $this->fecha_actualizacion);
        
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result['id_vivienda'])) {
                $this->id_vivienda = $result['id_vivienda'];
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error al crear la vivienda: " . $e->getMessage());
            throw $e;
        }
    }

    public function actualizar(): bool {
        try {
            $sql = "UPDATE vivienda SET 
                    direccion = :direccion,
                    numero = :numero,
                    tipo = :tipo,
                    sector = :sector,
                    estado = :estado
                    WHERE id_vivienda = :id_vivienda AND activo = true
                    RETURNING id_vivienda";
            
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindValue(':direccion', $this->direccion);
            $stmt->bindValue(':numero', $this->numero);
            $stmt->bindValue(':tipo', $this->tipo);
            $stmt->bindValue(':sector', $this->sector);
            $stmt->bindValue(':estado', $this->estado);
            $stmt->bindValue(':id_vivienda', $this->id_vivienda);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result !== false;
        } catch (PDOException $e) {
            error_log("Error al actualizar vivienda: " . $e->getMessage());
            throw $e;
        }
    }

    public function eliminar(): bool {
        try {
            $sql = "UPDATE vivienda SET activo = false
                    WHERE id_vivienda = :id_vivienda AND activo = true
                    RETURNING id_vivienda";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id_vivienda', $this->id_vivienda);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result !== false;
        } catch (PDOException $e) {
            error_log("Error al eliminar vivienda: " . $e->getMessage());
            throw $e;
        }
    }

        // Método para contar registros
        public function contar(array $filtros = []): int {
            try {
                $sql = 'SELECT COUNT(*) AS total FROM vivienda WHERE activo = true';
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
                error_log('Error al contar vivienda: ' . $e->getMessage());
                throw $e;
            }
        }

    // Getters y Setters
    public function getId_vivienda(): int {
        return $this->id_vivienda;
    }

    public function setId_vivienda(int $id_vivienda): void {
        $this->id_vivienda = $id_vivienda;
    }

    public function getDireccion(): string {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): void {
        $this->direccion = $direccion;
    }

    public function getNumero(): string {
        return $this->numero;
    }

    public function setNumero(string $numero): void {
        $this->numero = $numero;
    }

    public function getTipo(): string {
        return $this->tipo;
    }

    public function setTipo(string $tipo): void {
        $this->tipo = $tipo;
    }

    public function getSector(): string {
        return $this->sector;
    }

    public function setSector(string $sector): void {
        $this->sector = $sector;
    }

    public function getEstado(): ?string {
        return $this->estado;
    }

    public function setEstado(?string $estado): void {
        $this->estado = $estado;
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

<?php
namespace App\models;

use PDO;
use App\Core\Database;
use PDOException;

class EventoModel {
    private PDO $db;

    // Atributos
    private int $id_evento;
    private string $titulo;
    private string $descripcion;
    private string $fecha_evento;
    private string $lugar;
    private int $creado_por;
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
            $sql = "SELECT * FROM evento WHERE activo = true";
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
            error_log("Error al listar eventos: " . $e->getMessage());
            throw $e;
        }
    }

    public function obtenerPorId(int $id): ?array {
        try {
            $sql = "SELECT * FROM evento WHERE id_evento = :id AND activo = true";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener evento: " . $e->getMessage());
            throw $e;
        }
    }

    public function crear(): bool {
        try {
            $sql = "INSERT INTO evento (titulo, descripcion, fecha_evento, lugar, creado_por, fecha_registro)
                    VALUES (:titulo, :descripcion, :fecha_evento, :lugar, :creado_por, :fecha_registro)
                    RETURNING id_evento";
            
            $stmt = $this->db->prepare($sql);

            $ahora = date('Y-m-d H:i:s');
            $this->fecha_registro = $ahora;
        
            $stmt->bindValue(':titulo', $this->titulo);
            $stmt->bindValue(':descripcion', $this->descripcion);
            $stmt->bindValue(':fecha_evento', $this->fecha_evento);
            $stmt->bindValue(':lugar', $this->lugar);
            $stmt->bindValue(':creado_por', $this->creado_por);
            $stmt->bindValue(':fecha_registro', $this->fecha_registro);
        
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result['id_evento'])) {
                $this->id_evento = $result['id_evento'];
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error al crear el evento: " . $e->getMessage());
            throw $e;
        }
    }

    public function actualizar(): bool {
        try {
            $sql = "UPDATE evento SET 
                    titulo = :titulo,
                    descripcion = :descripcion,
                    fecha_evento = :fecha_evento,
                    lugar = :lugar,
                    creado_por = :creado_por
                    WHERE id_evento = :id_evento AND activo = true
                    RETURNING id_evento";
            
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindValue(':titulo', $this->titulo);
            $stmt->bindValue(':descripcion', $this->descripcion);
            $stmt->bindValue(':fecha_evento', $this->fecha_evento);
            $stmt->bindValue(':lugar', $this->lugar);
            $stmt->bindValue(':creado_por', $this->creado_por);
            $stmt->bindValue(':id_evento', $this->id_evento);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result !== false;
        } catch (PDOException $e) {
            error_log("Error al actualizar evento: " . $e->getMessage());
            throw $e;
        }
    }

    public function eliminar(): bool {
        try {
            $sql = "UPDATE evento SET activo = false
                    WHERE id_evento = :id_evento AND activo = true
                    RETURNING id_evento";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id_evento', $this->id_evento);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result !== false;
        } catch (PDOException $e) {
            error_log("Error al eliminar evento: " . $e->getMessage());
            throw $e;
        }
    }

    // Getters y Setters
    public function getId_evento(): int {
        return $this->id_evento;
    }

    public function setId_evento(int $id_evento): void {
        $this->id_evento = $id_evento;
    }

    public function getTitulo(): string {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void {
        $this->titulo = $titulo;
    }

    public function getDescripcion(): string {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): void {
        $this->descripcion = $descripcion;
    }

    public function getFecha_evento(): string {
        return $this->fecha_evento;
    }

    public function setFecha_evento(string $fecha_evento): void {
        $this->fecha_evento = $fecha_evento;
    }

    public function getLugar(): string {
        return $this->lugar;
    }

    public function setLugar(string $lugar): void {
        $this->lugar = $lugar;
    }

    public function getCreado_por(): int {
        return $this->creado_por;
    }

    public function setCreado_por(int $creado_por): void {
        $this->creado_por = $creado_por;
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

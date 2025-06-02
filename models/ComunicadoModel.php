<?php
namespace App\models;

use PDO;
use App\Core\Database;
use PDOException;

class ComunicadoModel {
    private PDO $db;

    // Atributos
    private int $id_comunicado;
    private int $id_usuario;
    private string $titulo;
    private string $contenido;
    private string $fecha_publicacion;
    private string $estado;
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
            $sql = "SELECT * FROM comunicado WHERE activo = true";
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
            error_log("Error al listar comunicados: " . $e->getMessage());
            throw $e;
        }
    }

    public function obtenerPorId(int $id): ?array {
        try {
            $sql = "SELECT * FROM comunicado WHERE id_comunicado = :id AND activo = true";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener comunicado: " . $e->getMessage());
            throw $e;
        }
    }

    public function crear(): bool {
        try {
            $sql = "INSERT INTO comunicado (id_usuario, titulo, contenido, fecha_publicacion, estado, fecha_registro)
                    VALUES (:id_usuario, :titulo, :contenido, :fecha_publicacion, :estado, :fecha_registro)
                    RETURNING id_comunicado";
            
            $stmt = $this->db->prepare($sql);

            $ahora = date('Y-m-d H:i:s');
            $this->fecha_registro = $ahora;
        
            $stmt->bindValue(':id_usuario', $this->id_usuario);
            $stmt->bindValue(':titulo', $this->titulo);
            $stmt->bindValue(':contenido', $this->contenido);
            $stmt->bindValue(':fecha_publicacion', $this->fecha_publicacion);
            $stmt->bindValue(':estado', $this->estado);
            $stmt->bindValue(':fecha_registro', $this->fecha_registro);
        
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result['id_comunicado'])) {
                $this->id_comunicado = $result['id_comunicado'];
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error al crear el comunicado: " . $e->getMessage());
            throw $e;
        }
    }

    public function actualizar(): bool {
        try {
            $sql = "UPDATE comunicado SET 
                    id_usuario = :id_usuario,
                    titulo = :titulo,
                    contenido = :contenido,
                    fecha_publicacion = :fecha_publicacion,
                    estado = :estado
                    WHERE id_comunicado = :id_comunicado AND activo = true
                    RETURNING id_comunicado";
            
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindValue(':id_usuario', $this->id_usuario);
            $stmt->bindValue(':titulo', $this->titulo);
            $stmt->bindValue(':contenido', $this->contenido);
            $stmt->bindValue(':fecha_publicacion', $this->fecha_publicacion);
            $stmt->bindValue(':estado', $this->estado);
            $stmt->bindValue(':id_comunicado', $this->id_comunicado);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result !== false;
        } catch (PDOException $e) {
            error_log("Error al actualizar comunicado: " . $e->getMessage());
            throw $e;
        }
    }

    public function eliminar(): bool {
        try {
            $sql = "UPDATE comunicado SET activo = false
                    WHERE id_comunicado = :id_comunicado AND activo = true
                    RETURNING id_comunicado";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id_comunicado', $this->id_comunicado);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result !== false;
        } catch (PDOException $e) {
            error_log("Error al eliminar comunicado: " . $e->getMessage());
            throw $e;
        }
    }

    // Getters y Setters
    public function getId_comunicado(): int {
        return $this->id_comunicado;
    }

    public function setId_comunicado(int $id_comunicado): void {
        $this->id_comunicado = $id_comunicado;
    }

    public function getId_usuario(): int {
        return $this->id_usuario;
    }

    public function setId_usuario(int $id_usuario): void {
        $this->id_usuario = $id_usuario;
    }

    public function getTitulo(): string {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void {
        $this->titulo = $titulo;
    }

    public function getContenido(): string {
        return $this->contenido;
    }

    public function setContenido(string $contenido): void {
        $this->contenido = $contenido;
    }

    public function getFecha_publicacion(): string {
        return $this->fecha_publicacion;
    }

    public function setFecha_publicacion(string $fecha_publicacion): void {
        $this->fecha_publicacion = $fecha_publicacion;
    }

    public function getEstado(): string {
        return $this->estado;
    }

    public function setEstado(string $estado): void {
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

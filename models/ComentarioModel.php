<?php
namespace App\models;

use PDO;
use App\Core\Database;
use PDOException;

class ComentarioModel {
    private PDO $db;

    // Atributos
    private int $id_comentario;
    private int $id_comunicado;
    private int $id_usuario;
    private string $contenido;
    private string $fecha_comentario;
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
            $sql = "INSERT INTO comentario (id_comunicado, id_usuario, contenido, fecha_comentario, fecha_registro, fecha_actualizacion)
                    VALUES (:id_comunicado, :id_usuario, :contenido, :fecha_comentario, :fecha_registro, :fecha_actualizacion)
                    RETURNING id_comentario";
            
            $stmt = $this->db->prepare($sql);

            $ahora = date('Y-m-d H:i:s');
            $this->fecha_registro = $ahora;
            $this->fecha_actualizacion = $ahora;
        
            $stmt->bindValue(':id_comunicado', $this->id_comunicado);
            $stmt->bindValue(':id_usuario', $this->id_usuario);
            $stmt->bindValue(':contenido', $this->contenido);
            $stmt->bindValue(':fecha_comentario', $this->fecha_comentario);
            $stmt->bindValue(':fecha_registro', $this->fecha_registro);
            $stmt->bindValue(':fecha_actualizacion', $this->fecha_actualizacion);
        
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result['id_comentario'])) {
                $this->id_comentario = $result['id_comentario'];
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error al crear el comentario: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function eliminar(): bool {
        try {
            $sql = "UPDATE comentario SET activo = :activo
                    WHERE id_comentario = :id_comentario 
                    RETURNING id_comentario, activo";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id_comentario", $this->id_comentario);
            $stmt->bindValue(":activo", false);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && isset($result["id_comentario"]) && isset($result["activo"])) {
                $this->activo = $result['activo'];
                return true;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Error al eliminar el comentario: " . $e->getMessage());
            throw $e;
        }
    }

    public function contar(array $filtros = []): int {
        try {
            $sql = "SELECT COUNT(*) AS total FROM comentario WHERE activo = true";
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
            error_log("Error al contar comentarios: " . $e->getMessage());
            throw $e;
        }
    }

    // Getters y Setters
    public function getId_comentario(): int {
        return $this->id_comentario;
    }

    public function setId_comentario(int $id_comentario): void {
        $this->id_comentario = $id_comentario;
    }

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

    public function getContenido(): string {
        return $this->contenido;
    }

    public function setContenido(string $contenido): void {
        $this->contenido = $contenido;
    }

    public function getFecha_comentario(): string {
        return $this->fecha_comentario;
    }

    public function setFecha_comentario(string $fecha_comentario): void {
        $this->fecha_comentario = $fecha_comentario;
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

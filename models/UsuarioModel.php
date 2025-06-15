<?php
namespace App\models;

use PDO;
use App\Core\Database;
use PDOException;

class UsuarioModel {
    private PDO $db;

    // Atributos 
    private int $id_usuario;
    private int $id_persona;
    private int $id_rol;
    private string $contrasena;
    private ?string $estado;
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
            $sql = "SELECT u.id_usuario,
               u.fecha_registro,
               p.cedula,
               p.nombres,
               p.apellidos,
			   r.id_rol,
               r.nombre as rol,
               u.estado,
               p.fecha_nacimiento
            FROM usuario u
            INNER JOIN persona p ON p.id_persona = u.id_persona
			INNER JOIN rol r ON r.id_rol = u.id_rol
            WHERE u.activo = true";
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
            error_log("Error al listar usuarios: " . $e->getMessage());
            throw $e;
        }
    }

    public function obtenerPorId(int $id): ?array {
        try {
            $sql = "SELECT u.id_usuario,
               u.fecha_registro,
               p.cedula,
               p.nombres,
               p.apellidos,
			   r.id_rol,
               r.nombre as rol,
               u.estado,
               p.fecha_nacimiento
            FROM usuario u
            INNER JOIN persona p ON p.id_persona = u.id_persona
			INNER JOIN rol r ON r.id_rol = u.id_rol
            WHERE id_usuario = :id AND activo = true";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener usuario: " . $e->getMessage());
            throw $e;
        }
    }

    public function crear(): bool {
        try {
            $sql = "INSERT INTO usuario (
                    id_persona, 
                    id_rol, 
                    contrasena, 
                    estado,
                    activo, 
                    fecha_registro,
                    fecha_actualizacion)
                    VALUES (
                    :id_persona, 
                    :id_rol, 
                    :contrasena, 
                    :estado,
                    :activo, 
                    :fecha_registro,
                    :fecha_actalizacion)
                    RETURNING id_usuario";
            
            $stmt = $this->db->prepare($sql);

            $ahora = date('Y-m-d H:i:s');
            $this->fecha_registro = $ahora;
            $this->fecha_actualizacion = $ahora;
        
            $stmt->bindValue(':id_persona', $this->id_persona);
            $stmt->bindValue(':id_rol', $this->id_rol);
            $stmt->bindValue(':contrasena', password_hash($this->contrasena, PASSWORD_BCRYPT));
            $stmt->bindValue(':estado', $this->estado);
            $stmt->bindValue(':activo', $this->activo);
            $stmt->bindValue(':fecha_registro', $this->fecha_registro);
            $stmt->bindValue(':fecha_actualizacion', $this->fecha_actualizacion);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result['id_usuario'])) {
                $this->id_usuario = $result['id_usuario'];
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error al crear usuario: " . $e->getMessage());
            throw $e;
        }
    }

    public function actualizar(): bool {
        try {
            $sql = "UPDATE usuario SET 
                    id_persona = :id_persona,
                    id_rol = :id_rol,
                    contrasena = :contrasena,
                    estado = :estado
                    WHERE id_usuario = :id_usuario AND activo = true
                    RETURNING id_usuario";
            
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindValue(':id_persona', $this->id_persona);
            $stmt->bindValue(':id_rol', $this->id_rol);
            $stmt->bindValue(':contrasena', password_hash($this->contrasena, PASSWORD_BCRYPT));
            $stmt->bindValue(':estado', $this->estado);
            $stmt->bindValue(':id_usuario', $this->id_usuario);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result !== false;
        } catch (PDOException $e) {
            error_log("Error al actualizar usuario: " . $e->getMessage());
            throw $e;
        }
    }

    public function eliminar(): bool {
        try {
            $sql = "UPDATE usuario SET activo = false
                    WHERE id_usuario = :id_usuario AND activo = true
                    RETURNING id_usuario";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id_usuario', $this->id_usuario);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result !== false;
        } catch (PDOException $e) {
            error_log("Error al eliminar usuario: " . $e->getMessage());
            throw $e;
        }
    }

    // Método para contar registros
    public function contar(array $filtros = []): int {
        try {
            $sql = 'SELECT COUNT(*) AS total FROM usuario WHERE activo = true';
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
            error_log('Error al contar usuarios: ' . $e->getMessage());
            throw $e;
        }
    }
    

    // Getters y setters
    public function getId_usuario(): int {
        return $this->id_usuario;
    }

    public function setId_usuario(int $id_usuario): void {
        $this->id_usuario = $id_usuario;
    }

    public function getId_persona(): int {
        return $this->id_persona;
    }

    public function setId_persona(int $id_persona): void {
        $this->id_persona = $id_persona;
    }

    public function getId_rol(): int {
        return $this->id_rol;
    }

    public function setId_rol(int $id_rol): void {
        $this->id_rol = $id_rol;
    }

    public function getContrasena(): string {
        return $this->contrasena;
    }

    public function setContrasena(string $contrasena): void {
        $auth = new AuthModel();
        $auth->validatePassword($contrasena);
        $this->contrasena = $contrasena;
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
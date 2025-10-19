<?php
namespace App\models;

use PDO;
use PDOException;
use App\Core\DatabaseInterface;

class UsuarioModel {
    private PDO $db;

    // Atributos 
    private int $id_usuario;
    private int $id_habitante;
    private int $id_rol;
    private string $username;
    private string $password_hash;
    private ?string $email = null;
    private ?string $foto_perfil = null;
    private ?string $token_recuperacion = null;
    private ?string $token_expiracion = null;
    private string $estado = 'ACTIVO';
    private bool $activo = true;
    private ?string $ultimo_acceso = null;
    private string $fecha_registro;
    private string $fecha_actualizacion;

    public function __construct(DatabaseInterface $database) {
        $this->db = $database->connect();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Métodos CRUD
    public function listar(array $filtros = []): array {
        try {
            $sql = "SELECT 
                u.id_usuario, u.username, u.email, u.foto_perfil, u.estado, 
                u.ultimo_acceso, u.fecha_registro, u.fecha_actualizacion,
                h.id_habitante, p.id_persona, p.cedula, p.nombres, p.apellidos, 
                p.telefono, p.genero, p.fecha_nacimiento,
                r.id_rol, r.nombre as rol_nombre
                FROM usuario u
                INNER JOIN habitante h ON h.id_habitante = u.id_habitante
                INNER JOIN persona p ON p.id_persona = h.id_persona
                INNER JOIN rol r ON r.id_rol = u.id_rol
                WHERE u.activo = true";
            
            $params = [];
            
            // Aplicar filtros
            if (!empty($filtros)) {
                $condiciones = [];
                foreach ($filtros as $campo => $valor) {
                    if (property_exists($this, $campo)) {
                        $condiciones[] = "u.$campo = :$campo";
                        $params[":$campo"] = $valor;
                    }
                }
                if (!empty($condiciones)) {
                    $sql .= " AND " . implode(" AND ", $condiciones);
                }
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            throw new \Exception("Error al listar usuarios: " . $e->getMessage());
        }
    }

    public function obtenerPorId(int $id): ?array {
        try {
            $sql = "SELECT 
                u.*, 
                h.id_persona, p.cedula, p.nombres, p.apellidos, p.telefono, p.email as email_personal,
                r.nombre as rol_nombre
                FROM usuario u
                INNER JOIN habitante h ON h.id_habitante = u.id_habitante
                INNER JOIN persona p ON p.id_persona = h.id_persona
                INNER JOIN rol r ON r.id_rol = u.id_rol
                WHERE u.id_usuario = :id AND u.activo = true";
                
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
            
        } catch (PDOException $e) {
            throw new \Exception("Error al obtener usuario: " . $e->getMessage());
        }
    }

    public function crear(): bool {
        try {
            $sql = "INSERT INTO usuario (
                id_habitante, id_rol, username, password_hash, email, 
                foto_perfil, estado, activo, fecha_registro, fecha_actualizacion
            ) VALUES (
                :id_habitante, :id_rol, :username, :password_hash, :email, 
                :foto_perfil, :estado, :activo, :fecha_registro, :fecha_actualizacion
            ) RETURNING id_usuario";
            
            $stmt = $this->db->prepare($sql);
            
            $this->fecha_registro = date('Y-m-d H:i:s');
            $this->fecha_actualizacion = date('Y-m-d H:i:s');
            
            $result = $stmt->execute([
                ':id_habitante' => $this->id_habitante,
                ':id_rol' => $this->id_rol,
                ':username' => $this->username,
                ':password_hash' => $this->password_hash,
                ':email' => $this->email,
                ':foto_perfil' => $this->foto_perfil,
                ':estado' => $this->estado,
                ':activo' => $this->activo,
                ':fecha_registro' => $this->fecha_registro,
                ':fecha_actualizacion' => $this->fecha_actualizacion
            ]);
            
            if ($result) {
                $this->id_usuario = (int)$this->db->lastInsertId();
                return true;
            }
            
            return false;
            
        } catch (PDOException $e) {
            if ($e->getCode() == 23505) { // Violación de clave única
                throw new \Exception("El nombre de usuario o correo electrónico ya está en uso");
            }
            throw new \Exception("Error al crear usuario: " . $e->getMessage());
        }
    }

    public function actualizar(): bool {
        try {
            $sql = "UPDATE usuario SET
                id_rol = :id_rol,
                username = :username,
                email = :email,
                foto_perfil = :foto_perfil,
                estado = :estado,
                activo = :activo,
                fecha_actualizacion = :fecha_actualizacion
                WHERE id_usuario = :id_usuario";
                
            $stmt = $this->db->prepare($sql);
            
            $this->fecha_actualizacion = date('Y-m-d H:i:s');
            
            return $stmt->execute([
                ':id_rol' => $this->id_rol,
                ':username' => $this->username,
                ':email' => $this->email,
                ':foto_perfil' => $this->foto_perfil,
                ':estado' => $this->estado,
                ':activo' => $this->activo,
                ':fecha_actualizacion' => $this->fecha_actualizacion,
                ':id_usuario' => $this->id_usuario
            ]);
            
        } catch (PDOException $e) {
            if ($e->getCode() == 23505) { // Violación de clave única
                throw new \Exception("El nombre de usuario o correo electrónico ya está en uso");
            }
            throw new \Exception("Error al actualizar usuario: " . $e->getMessage());
        }
    }

    public function eliminar(): bool {
        try {
            // Eliminación lógica
            $sql = "UPDATE usuario SET 
                activo = false,
                fecha_actualizacion = :fecha_actualizacion
                WHERE id_usuario = :id_usuario";
                
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':fecha_actualizacion' => date('Y-m-d H:i:s'),
                ':id_usuario' => $this->id_usuario
            ]);
            
        } catch (PDOException $e) {
            throw new \Exception("Error al eliminar usuario: " . $e->getMessage());
        }
    }

    // Métodos de autenticación
    public function verificarCredenciales(string $username, string $password): ?array {
        try {
            $sql = "SELECT u.*, h.id_persona, p.nombres, p.apellidos, r.nombre as rol_nombre
                    FROM usuario u
                    INNER JOIN habitante h ON h.id_habitante = u.id_habitante
                    INNER JOIN persona p ON p.id_persona = h.id_persona
                    INNER JOIN rol r ON r.id_rol = u.id_rol
                    WHERE (u.username = :username OR u.email = :email) 
                    AND u.activo = true";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':username' => $username, ':email' => $username]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($usuario && password_verify($password, $usuario['password_hash'])) {
                // Actualizar último acceso
                $this->actualizarUltimoAcceso($usuario['id_usuario']);
                return $usuario;
            }
            
            return null;
            
        } catch (PDOException $e) {
            throw new \Exception("Error al verificar credenciales: " . $e->getMessage());
        }
    }

    public function contar($filtros = null): int {
        try {
            $sql = "SELECT COUNT(*) FROM usuario";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new \Exception("Error al contar usuarios: " . $e->getMessage());
        }
    }

    private function actualizarUltimoAcceso(int $idUsuario): void {
        try {
            $sql = "UPDATE usuario SET 
                    ultimo_acceso = :ultimo_acceso
                    WHERE id_usuario = :id_usuario";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':ultimo_acceso' => date('Y-m-d H:i:s'),
                ':id_usuario' => $idUsuario
            ]);
            
        } catch (PDOException $e) {
            // No lanzamos excepción para no interrumpir el flujo de autenticación
            error_log("Error al actualizar último acceso: " . $e->getMessage());
        }
    }

    // Getters y Setters
    public function getIdUsuario(): int {
        return $this->id_usuario;
    }

    public function setIdUsuario(int $id_usuario): void {
        $this->id_usuario = $id_usuario;
    }

    public function getIdHabitante(): int {
        return $this->id_habitante;
    }

    public function setIdHabitante(int $id_habitante): void {
        $this->id_habitante = $id_habitante;
    }

    public function getIdRol(): int {
        return $this->id_rol;
    }

    public function setIdRol(int $id_rol): void {
        $this->id_rol = $id_rol;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function setUsername(string $username): void {
        $this->username = $username;
    }

    public function getPasswordHash(): string {
        return $this->password_hash;
    }

    public function setPassword(string $password): void {
        $this->password_hash = password_hash($password, PASSWORD_BCRYPT);
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(?string $email): void {
        $this->email = $email;
    }

    public function getFotoPerfil(): ?string {
        return $this->foto_perfil;
    }

    public function setFotoPerfil(?string $foto_perfil): void {
        $this->foto_perfil = $foto_perfil;
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

    public function getUltimoAcceso(): ?string {
        return $this->ultimo_acceso;
    }

    public function getFechaRegistro(): string {
        return $this->fecha_registro;
    }

    public function getFechaActualizacion(): string {
        return $this->fecha_actualizacion;
    }
}
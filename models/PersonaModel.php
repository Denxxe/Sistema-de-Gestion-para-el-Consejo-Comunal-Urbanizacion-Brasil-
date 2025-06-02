<?php
namespace App\models;

use PDO;
use App\Core\Database;
use PDOException;

class PersonaModel {
    private PDO $db;

    // Atributos
    private int $id_persona;
    private string $cedula;
    private string $nombres;
    private string $apellidos;
    private ?string $telefono = null;
    private ?string $correo = null;
    private ?string $direccion = null;
    private ?string $sexo = null;
    private ?string $estado = null;
    private ?string $fecha_nacimiento = null;
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
            $sql = "SELECT * FROM persona WHERE activo = true";
            $params = [];

            // Si hay filtros, los agregamos a la consulta
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
            error_log("Error al listar personas: " . $e->getMessage());
            throw $e;
        }
    }

    public function obtenerPorId(int $id): ?array {
        try {
            $sql = "SELECT * FROM persona WHERE id_persona = :id AND activo = true";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener persona: " . $e->getMessage());
            throw $e;
        }
    }

    public function crear(): bool {
        try {
            $sql = "INSERT INTO persona (nombres, apellidos, cedula, fecha_nacimiento, correo, telefono, fecha_registro)
                    VALUES (:nombres, :apellidos, :cedula, :fecha_nacimiento, :correo, :telefono, :fecha_registro)
                    RETURNING id_persona";
            
            $stmt = $this->db->prepare($sql);

            $ahora = date('Y-m-d H:i:s');
            $this->fecha_registro = $ahora;
        
            $stmt->bindValue(':nombres', $this->nombres);
            $stmt->bindValue(':apellidos', $this->apellidos);
            $stmt->bindValue(':cedula', $this->cedula);
            $stmt->bindValue(':fecha_nacimiento', $this->fecha_nacimiento);
            $stmt->bindValue(':correo', $this->correo);
            $stmt->bindValue(':telefono', $this->telefono);
            $stmt->bindValue(':fecha_registro', $this->fecha_registro);
        
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result['id_persona'])) {
                $this->id_persona = $result['id_persona'];
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error al crear persona: " . $e->getMessage());
            throw $e;
        }
    }

    public function actualizar(): bool {
        try {
            $sql = "UPDATE persona SET 
                    nombres = :nombres,
                    apellidos = :apellidos,
                    cedula = :cedula,
                    fecha_nacimiento = :fecha_nacimiento,
                    correo = :correo,
                    telefono = :telefono
                    WHERE id_persona = :id_persona AND activo = true
                    RETURNING id_persona";
            
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindValue(':nombres', $this->nombres);
            $stmt->bindValue(':apellidos', $this->apellidos);
            $stmt->bindValue(':cedula', $this->cedula);
            $stmt->bindValue(':fecha_nacimiento', $this->fecha_nacimiento);
            $stmt->bindValue(':correo', $this->correo);
            $stmt->bindValue(':telefono', $this->telefono);
            $stmt->bindValue(':id_persona', $this->id_persona);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result !== false;
        } catch (PDOException $e) {
            error_log("Error al actualizar persona: " . $e->getMessage());
            throw $e;
        }
    }

    public function eliminar(): bool {
        try {
            $sql = "UPDATE persona SET activo = false
                    WHERE id_persona = :id_persona AND activo = true
                    RETURNING id_persona";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id_persona', $this->id_persona);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result !== false;
        } catch (PDOException $e) {
            error_log("Error al eliminar persona: " . $e->getMessage());
            throw $e;
        }
    
    }

    // Getters y Setters
    public function getIdPersona(): ?int {
        return $this->id_persona;
    }

    public function setIdPersona(?int $id): void {
        $this->id_persona = $id;
    }

    public function getCedula(): ?string {
        return $this->cedula;
    }

    public function setCedula(?string $cedula): void {
        $this->cedula = $cedula;
    }

    public function getNombres(): ?string {
        return $this->nombres;
    }

    public function setNombres(?string $nombres): void {
        $this->nombres = $nombres;
    }

    public function getApellidos(): ?string {
        return $this->apellidos;
    }

    public function setApellidos(?string $apellidos): void {
        $this->apellidos = $apellidos;
    }

    public function getTelefono(): ?string {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): void {
        $this->telefono = $telefono;
    }

    public function getCorreo(): ?string {
        return $this->correo;
    }

    public function setCorreo(?string $correo): void {
        $this->correo = $correo;
    }

    public function getDireccion(): ?string {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): void {
        $this->direccion = $direccion;
    }

    public function getSexo(): ?string {
        return $this->sexo;
    }

    public function setSexo(?string $sexo): void {
        $this->sexo = $sexo;
    }

    public function getEstado(): ?string {
        return $this->estado;
    }

    public function setEstado(?string $estado): void {
        $this->estado = $estado;
    }

    public function getFecha_nacimiento(): ?string {
        return $this->fecha_nacimiento;
    }

    public function setFecha_nacimiento(?string $fecha_nacimiento): void {
        $this->fecha_nacimiento = $fecha_nacimiento;
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

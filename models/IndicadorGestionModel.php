<?php
namespace App\models;

use PDO;
use App\Core\Database;
use PDOException;

class IndicadorGestionModel {
    private PDO $db;

    // Atributos
    private int $id_indicador;
    private string $nombre;
    private string $descripcion;
    private float $valor;
    private string $fecha_registro;
    private int $generado_por;
    private bool $activo = true;
    private string $fecha_creacion;
    private string $fecha_actualizacion;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Métodos CRUD
    public function crear(): bool {
        try {
            $sql = "INSERT INTO indicador_gestion (nombre, descripcion, valor, fecha_registro, generado_por, fecha_creacion, fecha_actualizacion)
                    VALUES (:nombre, :descripcion, :valor, :fecha_registro, :generado_por, :fecha_creacion, :fecha_actualizacion)
                    RETURNING id_indicador";
            
            $stmt = $this->db->prepare($sql);

            $ahora = date('Y-m-d H:i:s');
            $this->fecha_creacion = $ahora;
            $this->fecha_actualizacion = $ahora;
        
            $stmt->bindValue(':nombre', $this->nombre);
            $stmt->bindValue(':descripcion', $this->descripcion);
            $stmt->bindValue(':valor', $this->valor);
            $stmt->bindValue(':fecha_registro', $this->fecha_registro);
            $stmt->bindValue(':generado_por', $this->generado_por);
            $stmt->bindValue(':fecha_creacion', $this->fecha_creacion);
            $stmt->bindValue(':fecha_actualizacion', $this->fecha_actualizacion);
        
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && isset($result['id_indicador'])) {
                $this->id_indicador = $result['id_indicador'];
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error al crear el indicador de gestión: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function eliminar(): bool {
        try {
            $sql = "UPDATE indicador_gestion SET activo = :activo
                    WHERE id_indicador = :id_indicador 
                    RETURNING id_indicador, activo";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id_indicador", $this->id_indicador);
            $stmt->bindValue(":activo", false);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && isset($result["id_indicador"]) && isset($result["activo"])) {
                $this->activo = $result['activo'];
                return true;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Error al eliminar el indicador de gestión: " . $e->getMessage());
            throw $e;
        }
    }

    // Getters y Setters
    public function getId_indicador(): int {
        return $this->id_indicador;
    }

    public function setId_indicador(int $id_indicador): void {
        $this->id_indicador = $id_indicador;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function getDescripcion(): string {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): void {
        $this->descripcion = $descripcion;
    }

    public function getValor(): float {
        return $this->valor;
    }

    public function setValor(float $valor): void {
        $this->valor = $valor;
    }

    public function getFecha_registro(): string {
        return $this->fecha_registro;
    }

    public function setFecha_registro(string $fecha_registro): void {
        $this->fecha_registro = $fecha_registro;
    }

    public function getGenerado_por(): int {
        return $this->generado_por;
    }

    public function setGenerado_por(int $generado_por): void {
        $this->generado_por = $generado_por;
    }

    public function getActivo(): bool {
        return $this->activo;
    }

    public function setActivo(bool $activo): void {
        $this->activo = $activo;
    }

    public function getFecha_creacion(): string {
        return $this->fecha_creacion;
    }

    public function setFecha_creacion(string $fecha_creacion): void {
        $this->fecha_creacion = $fecha_creacion;
    }

    public function getFecha_actualizacion(): string {
        return $this->fecha_actualizacion;
    }

    public function setFecha_actualizacion(string $fecha_actualizacion): void {
        $this->fecha_actualizacion = $fecha_actualizacion;
    }
}

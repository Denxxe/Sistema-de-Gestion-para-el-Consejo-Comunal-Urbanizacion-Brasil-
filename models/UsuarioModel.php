<?php
namespace Models;

use PDO;
use App\core\Database;
use PDOException;
class UsuarioModel {
    private PDO $db;

    private int $id_usuario;
    private int $id_habitante;
    private int $id_rol;
    private string $contrasena;
    private ?string $estado;
    private bool $activo;
    private string $fecha_registro;
    private string $fecha_actualizacion;

    public function __construct() {
        $this->db = new Database()->connect();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getId_usuario(): int {
        return $this->id_usuario;
    }

    public function setId_usuario(int $id_usuario): void {
        $this->id_usuario = $id_usuario;
    }

    public function getId_habitante(): int {
        return $this->id_habitante;
    }

    public function setId_habitante(int $id_habitante): void {
        $this->id_habitante = $id_habitante;
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
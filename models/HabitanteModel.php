<?php
namespace Models;

use PDO;
use App\core\Database;
use PDOException;

class HabitanteModel {
    private PDO $db;

    private int $id_habitante;
    private int $id_persona;
    private ?string $fecha_ingreso;
    private ?string $condicion;
    private bool $activo;
    private string $fecha_registro;
    private string $fecha_actualizacion;

    public function __construct() {
        $this->db = new Database()->connect();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Métodos CRUD aquí...












    // Getters y Setters
    public function getId_habitante(): int {
        return $this->id_habitante;
    }

    public function setId_habitante(int $id_habitante): void {
        $this->id_habitante = $id_habitante;
    }

    public function getId_persona(): int {
        return $this->id_persona;
    }

    public function setId_persona(int $id_persona): void {
        $this->id_persona = $id_persona;
    }

    public function getFecha_ingreso(): ?string {
        return $this->fecha_ingreso;
    }

    public function setFecha_ingreso(?string $fecha_ingreso): void {
        $this->fecha_ingreso = $fecha_ingreso;
    }

    public function getCondicion(): ?string {
        return $this->condicion;
    }

    public function setCondicion(?string $condicion): void {
        $this->condicion = $condicion;
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
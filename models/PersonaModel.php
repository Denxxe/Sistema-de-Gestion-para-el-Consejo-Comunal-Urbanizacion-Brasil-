<?php
namespace Models;

use PDO;
use App\core\Database;
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
        $this->db = new Database()->connect();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Métodos CRUD aquí...
    public function crear() {
        try{
            $sql = "INSERT INTO persona (cedula, nombres, apellidos, fecha_nacimiento, sexo, telefono, direccion, correo, estado,fecha_registro, fecha_actualizacion)
                    VALUES (:cedula, :nombres, :apellidos, :fecha_nacimiento, :sexo, :telefono, :direccion, :correo, :estado, :fecha_registro, :fecha_actualizacion)";
            
            $stmt = $this->db->prepare($sql);

            $ahora = date('Y-m-d H:i:s');
            $this->fecha_registro = $ahora;
            $this->fecha_actualizacion = $ahora;
        
            $stmt->bindValue(':cedula', $this->cedula);
            $stmt->bindValue(':nombres', $this->nombres);
            $stmt->bindValue(':apellidos', $this->apellidos);
            $stmt->bindValue(':fecha_nacimiento', $this->fecha_nacimiento);
            $stmt->bindValue(':sexo', $this->sexo);
            $stmt->bindValue(':telefono', $this->telefono);
            $stmt->bindValue(':direccion', $this->direccion);
            $stmt->bindValue(':correo', $this->correo);
            $stmt->bindValue(':estado', $this->estado);
            $stmt->bindValue(':fecha_registro', $this->fecha_registro);
            $stmt->bindValue(':fecha_actualizacion', $this->fecha_actualizacion);
        
            $stmt->execute();

            return $stmt->rowCount() > 0;
        }catch( PDOException $e){
            echo "Error al crear  una persona: " . $e->getMessage();
        }
    }
    
    // Getters y Setters
    public function getCedula(): string {
        return $this->cedula;
    }

    public function setCedula(string $cedula): void {
        $this->cedula = $cedula;
    }

    public function getNombres(): string {
        return $this->nombres;
    }

    public function setNombres(string $nombres): void {
        $this->nombres = $nombres;
    }

    // ... (y así con todos los atributos)
}

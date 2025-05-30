<?php
namespace App\controllers;

use Models\PersonaModel;
use Core\Response;

class PersonaController {
    public function crear(array $datos): array {
        $persona = new PersonaModel();

        $persona->setCedula($datos['cedula']);
        $persona->setNombres($datos['nombres']);
        $persona->setApellidos($datos['apellidos']);
        $persona->setFecha_nacimiento($datos['fecha_nacimiento'] ?? null);
        $persona->setSexo($datos['sexo'] ?? null);
        $persona->setTelefono($datos['telefono'] ?? null);
        $persona->setDireccion($datos['direccion'] ?? null);
        $persona->setCorreo($datos['correo'] ?? null);
        $persona->setEstado($datos['estado'] ?? null);

        if ($persona->crear()) {
            return Response::response201("Persona creada exitosamente");
        } else {
            return Response::response500("No se pudo crear la persona");
        }
    }
}

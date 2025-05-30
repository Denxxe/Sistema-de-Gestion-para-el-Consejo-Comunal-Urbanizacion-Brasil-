<?php
namespace App\controllers;

use App\models\PersonaModel;
use App\Core\Response;

class PersonaController {
    public function crear($datos = null): array {
        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $datos = json_decode(file_get_contents('php://input'), true);
        }

        if (!$datos) {
            return Response::response400('No se recibieron datos');
        }

        // Validación básica
        if (empty($datos['cedula']) || empty($datos['nombres']) || empty($datos['apellidos'])) {
            return Response::response400('Los campos cédula, nombres y apellidos son obligatorios');
        }

        try {
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
                $datos_persona = [
                    'cedula' => $persona->getCedula(),
                    'nombres' => $persona->getNombres(),
                    'apellidos' => $persona->getApellidos(),
                    'fecha_nacimiento' => $persona->getFecha_nacimiento(),
                    'sexo' => $persona->getSexo(),
                    'telefono' => $persona->getTelefono(),
                    'direccion' => $persona->getDireccion(),
                    'correo' => $persona->getCorreo(),
                    'estado' => $persona->getEstado()
                ];
                return Response::response201('Persona creada exitosamente', $datos_persona);
            } else {
                return Response::response500('No se pudo crear la persona');
            }
        } catch (\Exception $e) {
            return Response::response500('Error al procesar la solicitud: ' . $e->getMessage());
        }
    }
}

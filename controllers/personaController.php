<?php
namespace App\controllers;

use App\models\PersonaModel;
use App\Core\Response;

class PersonaController {
    public function listar($filtros = null): array {
        try {
            $persona = new PersonaModel();
            $filtros = $filtros ?? [];
            $personas = $persona->listar($filtros);
            
            if (empty($personas)) {
                return Response::response404('No se encontraron personas');
            }
            
            return Response::response200('Personas listadas exitosamente', $personas);
        } catch (\Exception $e) {
            return Response::response500('Error al listar personas: ' . $e->getMessage());
        }
    }

    public function obtenerPorId($id): array {
        try {
            $persona = new PersonaModel();
            $persona->setIdPersona($id);
            $persona = $persona->obtenerPorId($id);
            
            if (!$persona) {
                return Response::response404('Persona no encontrada');
            }
            
            return Response::response200('Persona obtenida exitosamente', $persona);
        } catch (\Exception $e) {
            return Response::response500('Error al obtener persona: ' . $e->getMessage());
        }
    }

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
                    'id' => $persona->getIdPersona(),
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

    public function actualizar($id, $datos = null): array {
        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $datos = json_decode(file_get_contents('php://input'), true);
        }

        if (!$datos) {
            return Response::response400('No se recibieron datos');
        }

        try {
            $persona = new PersonaModel();
            $persona->setIdPersona($id);

            // Actualizar solo los campos proporcionados
            if (isset($datos['cedula'])) $persona->setCedula($datos['cedula']);
            if (isset($datos['nombres'])) $persona->setNombres($datos['nombres']);
            if (isset($datos['apellidos'])) $persona->setApellidos($datos['apellidos']);
            if (isset($datos['fecha_nacimiento'])) $persona->setFecha_nacimiento($datos['fecha_nacimiento']);
            if (isset($datos['sexo'])) $persona->setSexo($datos['sexo']);
            if (isset($datos['telefono'])) $persona->setTelefono($datos['telefono']);
            if (isset($datos['direccion'])) $persona->setDireccion($datos['direccion']);
            if (isset($datos['correo'])) $persona->setCorreo($datos['correo']);
            if (isset($datos['estado'])) $persona->setEstado($datos['estado']);

            if ($persona->actualizar()) {
                return Response::response200('Persona actualizada exitosamente');
            } else {
                return Response::response500('No se pudo actualizar la persona');
            }
        } catch (\Exception $e) {
            return Response::response500('Error al actualizar persona: ' . $e->getMessage());
        }
    }

    public function eliminar($id): array {
        try {
            $persona = new PersonaModel();
            $persona->setIdPersona($id);

            if ($persona->eliminar()) {
                return Response::response200('Persona eliminada exitosamente');
            } else {
                return Response::response500('No se pudo eliminar la persona');
            }
        } catch (\Exception $e) {
            return Response::response500('Error al eliminar persona: ' . $e->getMessage());
        }
    }
}

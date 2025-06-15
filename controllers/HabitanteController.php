<?php
namespace App\controllers;

use App\models\HabitanteModel;
use App\Core\Response;

class HabitanteController 
{
    public function listar($filtros = null): array 
    {
        try {
            $habitante = new HabitanteModel();
            $filtros = $filtros ?? [];
            $habitantes = $habitante->listar($filtros);
            
            if (empty( $habitantes)) {
                return Response::response404('No se encontraron habitantes');
            }
            
            return Response::response200('Habitantes listados exitosamente', $habitantes);
        } catch (\Exception $e) {
            return Response::response500('Error al listar habitantes: ' . $e->getMessage());
        }
    }

    public function obtenerPorId($id): array 
    {
        try {
            $habitante = new HabitanteModel();
            $habitante->setId_habitante($id);
            $habitante = $habitante->obtenerPorId($id);
            
            if (!$habitante) {
                return Response::response404('Habitante no encontrada');
            }
            
            return Response::response200('Habitante obtenida exitosamente', $habitante);
        } catch (\Exception $e) {
            return Response::response500('Error al obtener habitante: ' . $e->getMessage());
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
        if (empty($datos['id_persona'])) {
            return Response::response400('La persona asociada es obligatoria');
        }

        try {
            $habitante = new HabitanteModel();

             $habitante->setId_persona($datos['id_persona']);
             $habitante->setFecha_ingreso($datos['fecha_ingreso'] ?? null);
             $habitante->setCondicion($datos['condicion'] ?? null);
             $datos_habitante = $habitante->crear();
             
            if ($datos_habitante) {
                return Response::response201('Habitante creado exitosamente', $datos_habitante);
            } else {
                return Response::response500('No se pudo crear el habitante');
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
            $habitante = new HabitanteModel();
            $habitante->setId_habitante($id);

            // Actualizar solo los campos proporcionados
            if (isset($datos['condicion']))  $habitante->setCondicion($datos['condicion']);
            if (isset($datos['id_persona']))  $habitante->setId_persona($datos['id_persona']);
            if (isset($datos['fecha_ingreso']))  $habitante->setFecha_ingreso($datos['fecha_ingreso']);

            if ($habitante->actualizar()) {
                return Response::response200('Habitante actualizado exitosamente');
            } else {
                return Response::response500('No se pudo actualizar el habitante');
            }
        } catch (\Exception $e) {
            return Response::response500('Error al actualizar habitante: ' . $e->getMessage());
        }
    }

    public function eliminar($id): array {
        try {
             $habitante = new HabitanteModel();
             $habitante->setId_habitante($id);

            if ( $habitante->eliminar()) {
                return Response::response200('Habitante eliminado exitosamente');
            } else {
                return Response::response500('No se pudo eliminar el habitante');
            }
        } catch (\Exception $e) {
            return Response::response500('Error al eliminar persona: ' . $e->getMessage());
        }
    }

    public function contar($filtros = null): array {
        try {
             $habitante = new HabitanteModel();
            $filtros = $filtros ?? [];
            $total =  $habitante->contar($filtros);

            return Response::response200('Conteo realizado exitosamente', ['total' => $total]);
        } catch (\Exception $e) {
            return Response::response500('Error al contar habitantes: ' . $e->getMessage());
        }
    }
}

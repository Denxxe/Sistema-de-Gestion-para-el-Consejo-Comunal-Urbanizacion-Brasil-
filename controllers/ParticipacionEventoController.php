<?php
namespace App\controllers;

use App\models\ParticipacionEventoModel;
use App\Core\Response;

class ParticipacionEventoController {

    public function listar($filtros = null): array {
        try {
            $model = new ParticipacionEventoModel();
            $filtros = $filtros ?? [];
            $data = $model->listar($filtros);
            if (empty($data)) {
                return Response::response404('No se encontraron participaciones en eventos');
            }
            return Response::response200('Participaciones listadas exitosamente', $data);
        } catch (\Exception $e) {
            return Response::response500('Error al listar participaciones: ' . $e->getMessage());
        }
    }

    public function obtenerPorId($id): array {
        try {
            $model = new ParticipacionEventoModel();
            $info = $model->obtenerPorId($id);
            if (!$info) {
                return Response::response404('Participación no encontrada');
            }
            return Response::response200('Participación encontrada', $info);
        } catch (\Exception $e) {
            return Response::response500('Error al obtener participación: ' . $e->getMessage());
        }
    }

    public function crear($datos = null): array {
        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $datos = json_decode(file_get_contents('php://input'), true);
        }
        
        if (!$datos) {
            return Response::response400('No se recibieron datos');
        }

        $camposRequeridos = ['id_evento', 'id_usuario'];
        foreach ($camposRequeridos as $campo) {
            if (!isset($datos[$campo])) {
                return Response::response400("El campo $campo es requerido");
            }
        }
        
        try {
            $model = new ParticipacionEventoModel();
            $model->setId_evento($datos['id_evento']);
            $model->setId_usuario($datos['id_usuario']);

            if ($model->crear()) {
                $nuevo = $model->obtenerPorId($model->getId_participacion());
                return Response::response201('Participación registrada exitosamente', $nuevo);
            }
            return Response::response500('Error al registrar la participación');
        } catch (\Exception $e) {
            return Response::response500('Error al registrar participación: ' . $e->getMessage());
        }
    }

    public function actualizar($id, $datos = null): array {
        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $datos = json_decode(file_get_contents('php://input'), true);
        }
        
        if (!$datos) {
            return Response::response400('No se recibieron datos para actualizar');
        }
        
        try {
            $model = new ParticipacionEventoModel();
            $participacion = $model->obtenerPorId($id);
            
            if (!$participacion) {
                return Response::response404('Participación no encontrada');
            }
            
            $model->setId_participacion($id);
            if (isset($datos['id_evento'])) $model->setId_evento($datos['id_evento']);
            if (isset($datos['id_usuario'])) $model->setId_usuario($datos['id_usuario']);

            if ($model->actualizar()) {
                $actualizado = $model->obtenerPorId($id);
                return Response::response200('Participación actualizada exitosamente', $actualizado);
            }
            return Response::response500('Error al actualizar la participación');
        } catch (\Exception $e) {
            return Response::response500('Error al actualizar participación: ' . $e->getMessage());
        }
    }

    public function eliminar($id): array {
        try {
            $model = new ParticipacionEventoModel();
            $participacion = $model->obtenerPorId($id);
            
            if (!$participacion) {
                return Response::response404('Participación no encontrada');
            }
            
            $model->setId_participacion($id);
            if ($model->eliminar()) {
                return Response::response204('Participación eliminada exitosamente');
            }
            return Response::response500('Error al eliminar la participación');
        } catch (\Exception $e) {
            return Response::response500('Error al eliminar participación: ' . $e->getMessage());
        }
    }

    public function contar($filtros = null): array {
        try {
            $model = new ParticipacionEventoModel();
            $total = $model->contar($filtros ?? []);
            return Response::response200('Conteo realizado', ['total' => $total]);
        } catch (\Exception $e) {
            return Response::response500('Error al contar participaciones: ' . $e->getMessage());
        }
    }
}

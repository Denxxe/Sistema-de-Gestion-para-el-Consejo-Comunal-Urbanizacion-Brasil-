<?php
namespace App\controllers;

use App\models\LiderCalleModel;
use App\Core\Response;

class LiderCalleController {

    public function listar($filtros = null): array {
        try {
            $model = new LiderCalleModel();
            $filtros = $filtros ?? [];
            $data = $model->listar($filtros);
            if (empty($data)) {
                return Response::response404('No se encontraron líderes de calle');
            }
            return Response::response200('Líderes de calle listados exitosamente', $data);
        } catch (\Exception $e) {
            return Response::response500('Error al listar líderes de calle: ' . $e->getMessage());
        }
    }

    public function obtenerPorId($id): array {
        try {
            $model = new LiderCalleModel();
            $info = $model->obtenerPorId($id);
            if (!$info) {
                return Response::response404('Líder de calle no encontrado');
            }
            return Response::response200('Líder de calle encontrado', $info);
        } catch (\Exception $e) {
            return Response::response500('Error al obtener líder de calle: ' . $e->getMessage());
        }
    }

    public function crear($datos = null): array {
        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $datos = json_decode(file_get_contents('php://input'), true);
        }
        
        if (!$datos) {
            return Response::response400('No se recibieron datos');
        }

        $camposRequeridos = ['id_habitante', 'sector', 'fecha_designacion'];
        foreach ($camposRequeridos as $campo) {
            if (!isset($datos[$campo])) {
                return Response::response400("El campo $campo es requerido");
            }
        }
        
        try {
            $model = new LiderCalleModel();
            $model->setId_habitante($datos['id_habitante']);
            $model->setSector($datos['sector']);
            $model->setFecha_designacion($datos['fecha_designacion']);
            if (isset($datos['zona'])) {
                $model->setZona($datos['zona']);
            }

            if ($model->crear()) {
                $nuevo = $model->obtenerPorId($model->getId_habitante());
                return Response::response201('Líder de calle asignado exitosamente', $nuevo);
            }
            return Response::response500('Error al asignar líder de calle');
        } catch (\Exception $e) {
            return Response::response500('Error al asignar líder de calle: ' . $e->getMessage());
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
            $model = new LiderCalleModel();
            $lider = $model->obtenerPorId($id);
            
            if (!$lider) {
                return Response::response404('Líder de calle no encontrado');
            }
            
            $model->setId_habitante($id);
            if (isset($datos['sector'])) $model->setSector($datos['sector']);
            if (isset($datos['zona'])) $model->setZona($datos['zona']);
            if (isset($datos['fecha_designacion'])) $model->setFecha_designacion($datos['fecha_designacion']);

            if ($model->actualizar()) {
                $actualizado = $model->obtenerPorId($id);
                return Response::response200('Líder de calle actualizado exitosamente', $actualizado);
            }
            return Response::response500('Error al actualizar el líder de calle');
        } catch (\Exception $e) {
            return Response::response500('Error al actualizar líder de calle: ' . $e->getMessage());
        }
    }

    public function eliminar($id): array {
        try {
            $model = new LiderCalleModel();
            $lider = $model->obtenerPorId($id);
            
            if (!$lider) {
                return Response::response404('Líder de calle no encontrado');
            }
            
            $model->setId_habitante($id);
            if ($model->eliminar()) {
                return Response::response204('Líder de calle eliminado exitosamente');
            }
            return Response::response500('Error al eliminar el líder de calle');
        } catch (\Exception $e) {
            return Response::response500('Error al eliminar líder de calle: ' . $e->getMessage());
        }
    }

    public function contar($filtros = null): array {
        try {
            $model = new LiderCalleModel();
            $total = $model->contar($filtros ?? []);
            return Response::response200('Conteo realizado', ['total' => $total]);
        } catch (\Exception $e) {
            return Response::response500('Error al contar líderes de calle: ' . $e->getMessage());
        }
    }
}

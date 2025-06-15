<?php
namespace App\controllers;

use App\models\LiderComunalModel;
use App\Core\Response;

class LiderComunalController {

    public function listar($filtros = null): array {
        try {
            $model = new LiderComunalModel();
            $filtros = $filtros ?? [];
            $data = $model->listar($filtros);
            if (empty($data)) {
                return Response::response404('No se encontraron líderes comunales');
            }
            return Response::response200('Líderes comunales listados exitosamente', $data);
        } catch (\Exception $e) {
            return Response::response500('Error al listar líderes comunales: ' . $e->getMessage());
        }
    }

    public function obtenerPorId($id): array {
        try {
            $model = new LiderComunalModel();
            $info = $model->obtenerPorId($id);
            if (!$info) {
                return Response::response404('Líder comunal no encontrado');
            }
            return Response::response200('Líder comunal encontrado', $info);
        } catch (\Exception $e) {
            return Response::response500('Error al obtener líder comunal: ' . $e->getMessage());
        }
    }

    public function crear($datos = null): array {
        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $datos = json_decode(file_get_contents('php://input'), true);
        }
        
        if (!$datos) {
            return Response::response400('No se recibieron datos');
        }

        $camposRequeridos = ['id_habitante', 'fecha_inicio'];
        foreach ($camposRequeridos as $campo) {
            if (!isset($datos[$campo])) {
                return Response::response400("El campo $campo es requerido");
            }
        }
        
        try {
            $model = new LiderComunalModel();
            $model->setId_habitante($datos['id_habitante']);
            $model->setFecha_inicio($datos['fecha_inicio']);
            
            if (isset($datos['fecha_fin'])) {
                $model->setFecha_fin($datos['fecha_fin']);
            }
            if (isset($datos['observaciones'])) {
                $model->setObservaciones($datos['observaciones']);
            }

            if ($model->crear()) {
                $nuevo = $model->obtenerPorId($model->getId_habitante());
                return Response::response201('Líder comunal asignado exitosamente', $nuevo);
            }
            return Response::response500('Error al asignar líder comunal');
        } catch (\Exception $e) {
            return Response::response500('Error al asignar líder comunal: ' . $e->getMessage());
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
            $model = new LiderComunalModel();
            $lider = $model->obtenerPorId($id);
            
            if (!$lider) {
                return Response::response404('Líder comunal no encontrado');
            }
            
            $model->setId_habitante($id);
            if (isset($datos['fecha_inicio'])) $model->setFecha_inicio($datos['fecha_inicio']);
            if (array_key_exists('fecha_fin', $datos)) $model->setFecha_fin($datos['fecha_fin']);
            if (isset($datos['observaciones'])) $model->setObservaciones($datos['observaciones']);

            if ($model->actualizar()) {
                $actualizado = $model->obtenerPorId($id);
                return Response::response200('Líder comunal actualizado exitosamente', $actualizado);
            }
            return Response::response500('Error al actualizar el líder comunal');
        } catch (\Exception $e) {
            return Response::response500('Error al actualizar líder comunal: ' . $e->getMessage());
        }
    }

    public function eliminar($id): array {
        try {
            $model = new LiderComunalModel();
            $lider = $model->obtenerPorId($id);
            
            if (!$lider) {
                return Response::response404('Líder comunal no encontrado');
            }
            
            $model->setId_habitante($id);
            if ($model->eliminar()) {
                return Response::response204('Líder comunal eliminado exitosamente');
            }
            return Response::response500('Error al eliminar el líder comunal');
        } catch (\Exception $e) {
            return Response::response500('Error al eliminar líder comunal: ' . $e->getMessage());
        }
    }

    public function contar($filtros = null): array {
        try {
            $model = new LiderComunalModel();
            $total = $model->contar($filtros ?? []);
            return Response::response200('Conteo realizado', ['total' => $total]);
        } catch (\Exception $e) {
            return Response::response500('Error al contar líderes comunales: ' . $e->getMessage());
        }
    }
}

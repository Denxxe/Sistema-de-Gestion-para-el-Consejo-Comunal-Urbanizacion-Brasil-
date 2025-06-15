<?php
namespace App\controllers;

use App\models\CargaFamiliarModel;
use App\Core\Response;

class CargaFamiliarController {

    public function listar($filtros = null): array {
        try {
            $model = new CargaFamiliarModel();
            $filtros = $filtros ?? [];
            $data = $model->listar($filtros);
            if (empty($data)) {
                return Response::response404('No se encontraron cargas familiares');
            }
            return Response::response200('Cargas familiares listadas exitosamente', $data);
        } catch (\Exception $e) {
            return Response::response500('Error al listar cargas familiares: ' . $e->getMessage());
        }
    }

    public function obtenerPorId($id): array {
        try {
            $model = new CargaFamiliarModel();
            $info = $model->obtenerPorId($id);
            if (!$info) {
                return Response::response404('Carga familiar no encontrada');
            }
            return Response::response200('Carga familiar encontrada', $info);
        } catch (\Exception $e) {
            return Response::response500('Error al obtener carga familiar: ' . $e->getMessage());
        }
    }

    public function crear($datos = null): array {
        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $datos = json_decode(file_get_contents('php://input'), true);
        }
        
        if (!$datos) {
            return Response::response400('No se recibieron datos');
        }
    
        $camposRequeridos = ['id_habitante', 'id_jefe', 'parentesco'];
        foreach ($camposRequeridos as $campo) {
            if (!isset($datos[$campo])) {
                return Response::response400("El campo $campo es requerido");
            }
        }
        
        try {
            $model = new CargaFamiliarModel();
            $model->setId_habitante($datos['id_habitante']);
            $model->setId_jefe($datos['id_jefe']);
            $model->setParentesco($datos['parentesco']);
    
            if ($model->crear()) {
                $nuevo = $model->obtenerPorId($model->getId_carga());
                return Response::response201('Carga familiar creada exitosamente', $nuevo);
            }
            return Response::response500('Error al crear carga familiar');
        } catch (\Exception $e) {
            return Response::response500('Error al crear carga familiar: ' . $e->getMessage());
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
            $model = new CargaFamiliarModel();
            $carga = $model->obtenerPorId($id);
            
            if (!$carga) {
                return Response::response404('Carga familiar no encontrada');
            }
            
            $model->setId_carga($id);
            if (isset($datos['id_habitante'])) $model->setId_habitante($datos['id_habitante']);
            if (isset($datos['id_jefe'])) $model->setId_jefe($datos['id_jefe']);
            if (isset($datos['parentesco'])) $model->setParentesco($datos['parentesco']);
    
            if ($model->actualizar()) {
                $actualizado = $model->obtenerPorId($id);
                return Response::response200('Carga familiar actualizada exitosamente', $actualizado);
            }
            return Response::response500('Error al actualizar la carga familiar');
        } catch (\Exception $e) {
            return Response::response500('Error al actualizar carga familiar: ' . $e->getMessage());
        }
    }
    
    public function eliminar($id): array {
        try {
            $model = new CargaFamiliarModel();
            $carga = $model->obtenerPorId($id);
            
            if (!$carga) {
                return Response::response404('Carga familiar no encontrada');
            }
            
            $model->setId_carga($id);
            if ($model->eliminar()) {
                return Response::response204('Carga familiar eliminada exitosamente');
            }
            return Response::response500('Error al eliminar la carga familiar');
        } catch (\Exception $e) {
            return Response::response500('Error al eliminar carga familiar: ' . $e->getMessage());
        }
    }

    public function contar($filtros = null): array {
        try {
            $model = new CargaFamiliarModel();
            $total = $model->contar($filtros ?? []);
            return Response::response200('Conteo realizado', ['total' => $total]);
        } catch (\Exception $e) {
            return Response::response500('Error al contar cargas familiares: ' . $e->getMessage());
        }
    }
}

<?php
namespace App\controllers;

use App\models\ConceptoPagoModel;
use App\Core\Response;

class ConceptoPagoController {

    public function listar($filtros = null): array {
        try {
            $model = new ConceptoPagoModel();
            $filtros = $filtros ?? [];
            $data = $model->listar($filtros);
            if (empty($data)) {
                return Response::response404('No se encontraron conceptos de pago');
            }
            return Response::response200('Conceptos de pago listados exitosamente', $data);
        } catch (\Exception $e) {
            return Response::response500('Error al listar conceptos de pago: ' . $e->getMessage());
        }
    }

    public function obtenerPorId($id): array {
        try {
            $model = new ConceptoPagoModel();
            $info = $model->obtenerPorId($id);
            if (!$info) {
                return Response::response404('Concepto de pago no encontrado');
            }
            return Response::response200('Concepto de pago encontrado', $info);
        } catch (\Exception $e) {
            return Response::response500('Error al obtener concepto de pago: ' . $e->getMessage());
        }
    }

    public function crear($datos = null): array {
        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $datos = json_decode(file_get_contents('php://input'), true);
        }
        
        if (!$datos) {
            return Response::response400('No se recibieron datos');
        }

        if (!is_numeric($datos['monto']) || $datos['monto'] <= 0) {
            return Response::response400('El monto debe ser un valor numérico mayor a 0');
        }
        
        $camposRequeridos = ['nombre', 'monto'];
        foreach ($camposRequeridos as $campo) {
            if (!isset($datos[$campo])) {
                return Response::response400("El campo $campo es requerido");
            }
        }
        
        try {
            $model = new ConceptoPagoModel();
            $model->setNombre($datos['nombre']);
            $model->setMonto($datos['monto']);
            if (isset($datos['descripcion'])) {
                $model->setDescripcion($datos['descripcion']);
            }

            if ($model->crear()) {
                $nuevo = $model->obtenerPorId($model->getId_concepto());
                return Response::response201('Concepto de pago creado exitosamente', $nuevo);
            }
            return Response::response500('Error al crear el concepto de pago');
        } catch (\Exception $e) {
            return Response::response500('Error al crear concepto de pago: ' . $e->getMessage());
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
            $model = new ConceptoPagoModel();
            $concepto = $model->obtenerPorId($id);
            
            if (!$concepto) {
                return Response::response404('Concepto de pago no encontrado');
            }
            
            $model->setId_concepto($id);
            if (isset($datos['nombre'])) $model->setNombre($datos['nombre']);
            if (isset($datos['descripcion'])) $model->setDescripcion($datos['descripcion']);
            if (isset($datos['monto'])) $model->setMonto($datos['monto']);

            if ($model->actualizar()) {
                $actualizado = $model->obtenerPorId($id);
                return Response::response200('Concepto de pago actualizado exitosamente', $actualizado);
            }
            return Response::response500('Error al actualizar el concepto de pago');
        } catch (\Exception $e) {
            return Response::response500('Error al actualizar concepto de pago: ' . $e->getMessage());
        }
    }

    public function eliminar($id): array {
        try {
            $model = new ConceptoPagoModel();
            $concepto = $model->obtenerPorId($id);
            
            if (!$concepto) {
                return Response::response404('Concepto de pago no encontrado');
            }
            
            $model->setId_concepto($id);
            if ($model->eliminar()) {
                return Response::response204('Concepto de pago eliminado exitosamente');
            }
            return Response::response500('Error al eliminar el concepto de pago');
        } catch (\Exception $e) {
            return Response::response500('Error al eliminar concepto de pago: ' . $e->getMessage());
        }
    }

    public function contar($filtros = null): array {
        try {
            $model = new ConceptoPagoModel();
            $total = $model->contar($filtros ?? []);
            return Response::response200('Conteo realizado', ['total' => $total]);
        } catch (\Exception $e) {
            return Response::response500('Error al contar conceptos de pago: ' . $e->getMessage());
        }
    }
}

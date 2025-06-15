<?php
namespace App\controllers;

use App\models\IndicadorGestionModel;
use App\models\UsuarioModel;
use App\models\PagoModel;
use App\Core\Response;

class IndicadorGestionController {

    public function listar($filtros = null): array {
        try {
            $model = new IndicadorGestionModel();
            $filtros = $filtros ?? [];
            $data = $model->listar($filtros);
            if (empty($data)) {
                return Response::response404('No se encontraron indicadores');
            }
            return Response::response200('Indicadores listados exitosamente', $data);
        } catch (\Exception $e) {
            return Response::response500('Error al listar indicadores: ' . $e->getMessage());
        }
    }

    public function obtenerPorId($id): array {
        try {
            $model = new IndicadorGestionModel();
            $info = $model->obtenerPorId($id);
            if (!$info) {
                return Response::response404('Indicador no encontrado');
            }
            return Response::response200('Indicador encontrado', $info);
        } catch (\Exception $e) {
            return Response::response500('Error al obtener indicador: ' . $e->getMessage());
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
        if (empty($datos['nombre']) || !isset($datos['valor'])) {
            return Response::response400('Los campos nombre y valor son obligatorios');
        }
        try {
            $model = new IndicadorGestionModel();
            $model->setNombre($datos['nombre']);
            $model->setDescripcion($datos['descripcion'] ?? '');
            $model->setValor($datos['valor']);
            $model->setFecha_registro(date('Y-m-d'));
            $model->setGenerado_por($datos['generado_por'] ?? null);
            if ($model->crear()) {
                $nuevo = $model->obtenerPorId($model->getId_indicador());
                return Response::response201('Indicador creado exitosamente', $nuevo);
            }
            return Response::response500('Error al crear indicador');
        } catch (\Exception $e) {
            return Response::response500('Error al crear indicador: ' . $e->getMessage());
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
            $model = new IndicadorGestionModel();
            $model->setId_indicador($id);
            // establecemos valores solo si existen en $datos para permitir actualizaciones parciales
            if (isset($datos['nombre'])) $model->setNombre($datos['nombre']);
            if (isset($datos['descripcion'])) $model->setDescripcion($datos['descripcion']);
            if (isset($datos['valor'])) $model->setValor($datos['valor']);
            if (isset($datos['fecha_registro'])) $model->setFecha_registro($datos['fecha_registro']);
            if (isset($datos['generado_por'])) $model->setGenerado_por($datos['generado_por']);
            if ($model->actualizar()) {
                $actual = $model->obtenerPorId($id);
                return Response::response200('Indicador actualizado', $actual);
            }
            return Response::response500('Error al actualizar indicador');
        } catch (\Exception $e) {
            return Response::response500('Error al actualizar indicador: ' . $e->getMessage());
        }
    }

    public function eliminar($id): array {
        try {
            $model = new IndicadorGestionModel();
            $model->setId_indicador($id);
            if ($model->eliminar()) {
                return Response::response204('Indicador eliminado');
            }
            return Response::response500('Error al eliminar indicador');
        } catch (\Exception $e) {
            return Response::response500('Error al eliminar indicador: ' . $e->getMessage());
        }
    }

    public function contar($filtros = null): array {
        try {
            $model = new IndicadorGestionModel();
            $total = $model->contar($filtros ?? []);
            return Response::response200('Conteo realizado', ['total' => $total]);
        } catch (\Exception $e) {
            return Response::response500('Error al contar indicadores: ' . $e->getMessage());
        }
    }

    /**
     * Devuelve estadísticas básicas del sistema: cantidad total de usuarios y de pagos activos.
     * Endpoint sugerido: GET /indicadores/basicos
     */
    public function basicos(): array {
        try {
            // total usuarios
            $usuarioModel = new \App\models\UsuarioModel();
            $totalUsuarios = $usuarioModel->contar([]);

            // total pagos
            $pagoModel = new \App\models\PagoModel();
            $totalPagos = $pagoModel->contar([]);

            $data = [
                'total_usuarios' => $totalUsuarios,
                'total_pagos'    => $totalPagos
            ];

            return Response::response200('Indicadores básicos generados', $data);
        } catch (\Exception $e) {
            return Response::response500('Error al generar indicadores básicos: ' . $e->getMessage());
        }
    }
}

<?php
namespace App\controllers;

use App\models\ComunicadoModel;
use App\Core\Response;

class ComunicadoController {

    public function listar($filtros = null): array {
        try {
            $model = new ComunicadoModel();
            $filtros = $filtros ?? [];
            $data = $model->listar($filtros);
            if (empty($data)) {
                return Response::response404('No se encontraron comunicados');
            }
            return Response::response200('Comunicados listados exitosamente', $data);
        } catch (\Exception $e) {
            return Response::response500('Error al listar comunicados: ' . $e->getMessage());
        }
    }

    public function obtenerPorId($id): array {
        try {
            $model = new ComunicadoModel();
            $info = $model->obtenerPorId($id);
            if (!$info) {
                return Response::response404('Comunicado no encontrado');
            }
            return Response::response200('Comunicado encontrado', $info);
        } catch (\Exception $e) {
            return Response::response500('Error al obtener comunicado: ' . $e->getMessage());
        }
    }

    public function crear($datos = null): array {
        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $datos = json_decode(file_get_contents('php://input'), true);
        }
        
        if (!$datos) {
            return Response::response400('No se recibieron datos');
        }

        $camposRequeridos = ['id_usuario', 'titulo', 'contenido', 'fecha_publicacion', 'estado'];
        foreach ($camposRequeridos as $campo) {
            if (!isset($datos[$campo])) {
                return Response::response400("El campo $campo es requerido");
            }
        }
        
        try {
            $model = new ComunicadoModel();
            $model->setId_usuario($datos['id_usuario']);
            $model->setTitulo($datos['titulo']);
            $model->setContenido($datos['contenido']);
            $model->setFecha_publicacion($datos['fecha_publicacion']);
            $model->setEstado($datos['estado']);

            if ($model->crear()) {
                $nuevo = $model->obtenerPorId($model->getId_comunicado());
                return Response::response201('Comunicado creado exitosamente', $nuevo);
            }
            return Response::response500('Error al crear el comunicado');
        } catch (\Exception $e) {
            return Response::response500('Error al crear comunicado: ' . $e->getMessage());
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
            $model = new ComunicadoModel();
            $comunicado = $model->obtenerPorId($id);
            
            if (!$comunicado) {
                return Response::response404('Comunicado no encontrado');
            }
            
            $model->setId_comunicado($id);
            if (isset($datos['titulo'])) $model->setTitulo($datos['titulo']);
            if (isset($datos['contenido'])) $model->setContenido($datos['contenido']);
            if (isset($datos['fecha_publicacion'])) $model->setFecha_publicacion($datos['fecha_publicacion']);
            if (isset($datos['estado'])) $model->setEstado($datos['estado']);

            if ($model->actualizar()) {
                $actualizado = $model->obtenerPorId($id);
                return Response::response200('Comunicado actualizado exitosamente', $actualizado);
            }
            return Response::response500('Error al actualizar el comunicado');
        } catch (\Exception $e) {
            return Response::response500('Error al actualizar comunicado: ' . $e->getMessage());
        }
    }

    public function eliminar($id): array {
        try {
            $model = new ComunicadoModel();
            $comunicado = $model->obtenerPorId($id);
            
            if (!$comunicado) {
                return Response::response404('Comunicado no encontrado');
            }
            
            $model->setId_comunicado($id);
            if ($model->eliminar()) {
                return Response::response204('Comunicado eliminado exitosamente');
            }
            return Response::response500('Error al eliminar el comunicado');
        } catch (\Exception $e) {
            return Response::response500('Error al eliminar comunicado: ' . $e->getMessage());
        }
    }

    public function contar($filtros = null): array {
        try {
            $model = new ComunicadoModel();
            $total = $model->contar($filtros ?? []);
            return Response::response200('Conteo realizado', ['total' => $total]);
        } catch (\Exception $e) {
            return Response::response500('Error al contar comunicados: ' . $e->getMessage());
        }
    }
}

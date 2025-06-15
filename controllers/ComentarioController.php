<?php
namespace App\controllers;

use App\models\ComentarioModel;
use App\Core\Response;

class ComentarioController {

    public function listar($filtros = null): array {
        try {
            $model = new ComentarioModel();
            $filtros = $filtros ?? [];
            $data = $model->listar($filtros);
            if (empty($data)) {
                return Response::response404('No se encontraron comentarios');
            }
            return Response::response200('Comentarios listados exitosamente', $data);
        } catch (\Exception $e) {
            return Response::response500('Error al listar comentarios: ' . $e->getMessage());
        }
    }

    public function obtenerPorId($id): array {
        try {
            $model = new ComentarioModel();
            $info = $model->obtenerPorId($id);
            if (!$info) {
                return Response::response404('Comentario no encontrado');
            }
            return Response::response200('Comentario encontrado', $info);
        } catch (\Exception $e) {
            return Response::response500('Error al obtener comentario: ' . $e->getMessage());
        }
    }

    public function crear($datos = null): array {
        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $datos = json_decode(file_get_contents('php://input'), true);
        }
        if (!$datos) {
            return Response::response400('No se recibieron datos');
        }
        
        try {
            $model = new ComentarioModel();
            $model->setId_usuario($datos['id_usuario']);
            $model->setId_comunicado($datos['id_comunicado']);
            $model->setContenido($datos['comentario']);

            if ($model->crear()) {
                $nuevo = $model->listar(['id_comentario' => $model->getId_comentario()]);
                return Response::response201('Comentario creado exitosamente', $nuevo);
            }
            return Response::response500('Error al crear comentario');
        } catch (\Exception $e) {
            return Response::response500('Error al crear comentario: ' . $e->getMessage());
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
            $model = new ComentarioModel();
            $model->setId_comentario($id);
            
            if (isset($datos['comentario'])) $model->setContenido($datos['comentario']);

            if ($model->editar($id)) {
                $actual = $model->listar(['id_comentario' => $id]);
                return Response::response200('Comentario actualizado', $actual);
            }
            return Response::response500('Error al actualizar comentario');
        } catch (\Exception $e) {
            return Response::response500('Error al actualizar comentario: ' . $e->getMessage());
        }
    }

    public function eliminar($id): array {
        try {
            $model = new ComentarioModel();
            $model->setId_comentario($id);
            if ($model->eliminar()) {
                return Response::response204('Comentario eliminado');
            }
            return Response::response500('Error al eliminar comentario');
        } catch (\Exception $e) {
            return Response::response500('Error al eliminar comentario: ' . $e->getMessage());
        }
    }

    public function contar($filtros = null): array {
        try {
            $model = new ComentarioModel();
            $total = $model->contar($filtros ?? []);
            return Response::response200('Conteo realizado', ['total' => $total]);
        } catch (\Exception $e) {
            return Response::response500('Error al contar comentarios: ' . $e->getMessage());
        }
    }
}

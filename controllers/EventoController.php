<?php
namespace App\controllers;

use App\models\EventoModel;
use App\Core\Response;

class EventoController {

    public function listar($filtros = null): array {
        try {
            $evento = new EventoModel();
            $filtros = $filtros ?? [];
            $data = $evento->listar($filtros);
            if (empty($data)) {
                return Response::response404('No se encontraron eventos');
            }
            return Response::response200('Eventos listados exitosamente', $data);
        } catch (\Exception $e) {
            return Response::response500('Error al listar eventos: ' . $e->getMessage());
        }
    }

    public function obtenerPorId($id): array {
        try {
            $evento = new EventoModel();
            $info = $evento->obtenerPorId($id);
            if (!$info) {
                return Response::response404('Evento no encontrado');
            }
            return Response::response200('Evento encontrado', $info);
        } catch (\Exception $e) {
            return Response::response500('Error al obtener evento: ' . $e->getMessage());
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
            $evento = new EventoModel();
            $evento->setTitulo($datos['titulo']);
            $evento->setDescripcion($datos['descripcion']);
            $evento->setFecha_evento($datos['fecha_evento']);
            $evento->setLugar($datos['lugar']);
            $evento->setCreado_por($datos['id_usuario_creador']);
            $evento->setEstado($datos['estado'] ?? 'pendiente');

            if ($evento->crear()) {
                $nuevo = $evento->obtenerPorId($evento->getId_evento());
                return Response::response201('Evento creado exitosamente', $nuevo);
            }
            return Response::response500('Error al crear evento');
        } catch (\Exception $e) {
            return Response::response500('Error al crear evento: ' . $e->getMessage());
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
            $evento = new EventoModel();
            $evento->setId_evento($id);
            
            if (isset($datos['titulo'])) $evento->setTitulo($datos['titulo']);
            if (isset($datos['descripcion'])) $evento->setDescripcion($datos['descripcion']);
            if (isset($datos['fecha_evento'])) $evento->setFecha_evento($datos['fecha_evento']);
            if (isset($datos['lugar'])) $evento->setLugar($datos['lugar']);
            if (isset($datos['estado'])) $evento->setEstado($datos['estado']);

            if ($evento->actualizar()) {
                $actual = $evento->obtenerPorId($id);
                return Response::response200('Evento actualizado', $actual);
            }
            return Response::response500('Error al actualizar evento');
        } catch (\Exception $e) {
            return Response::response500('Error al actualizar evento: ' . $e->getMessage());
        }
    }

    public function eliminar($id): array {
        try {
            $evento = new EventoModel();
            $evento->setId_evento($id);
            if ($evento->eliminar()) {
                return Response::response204('Evento eliminado');
            }
            return Response::response500('Error al eliminar evento');
        } catch (\Exception $e) {
            return Response::response500('Error al eliminar evento: ' . $e->getMessage());
        }
    }

    public function contar($filtros = null): array {
        try {
            $evento = new EventoModel();
            $total = $evento->contar($filtros ?? []);
            return Response::response200('Conteo realizado', ['total' => $total]);
        } catch (\Exception $e) {
            return Response::response500('Error al contar eventos: ' . $e->getMessage());
        }
    }
}

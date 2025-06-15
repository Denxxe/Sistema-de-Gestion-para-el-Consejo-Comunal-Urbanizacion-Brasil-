<?php
namespace App\controllers;

use App\models\ViviendaModel;
use App\Core\Response;

class ViviendaController {
    public function listar($filtros = null): array {
        try {
            $vivienda = new ViviendaModel();
            $filtros = $filtros ?? [];
            $viviendas = $vivienda->listar($filtros);
            
            if (empty($viviendas)) {
                return Response::response404('No se encontraron viviendas');
            }
            
            return Response::response200('viviendas listadas exitosamente', $viviendas);
        } catch (\Exception $e) {
            return Response::response500('Error al listar viviendas: ' . $e->getMessage());
        }
    }

    public function obtenerPorId($id): array {
        try {
            $vivienda = new ViviendaModel();
            $vivienda->setId_vivienda($id);
            $vivienda = $vivienda->obtenerPorId($id);
            
            if (!$vivienda) {
                return Response::response404('Vivienda no encontrada');
            }
            
            return Response::response200('Vivienda obtenida exitosamente', $vivienda);
        } catch (\Exception $e) {
            return Response::response500('Error al obtener vivienda: ' . $e->getMessage());
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
        if (empty($datos['direccion']) || empty($datos['numero']) || empty($datos['tipo']) || empty($datos['sector'])) {
            return Response::response400('Los campos direccion, numero, tipo y sector son obligatorios');
        }

        try {
            $vivienda = new ViviendaModel();

            $vivienda->setDireccion($datos['direccion']);
            $vivienda->setNumero($datos['numero']);
            $vivienda->setTipo($datos['tipo']);
            $vivienda->setSector($datos['sector'] ?? null);
            $vivienda->setEstado($datos['estado'] ?? null);

            if ($vivienda->crear()) {
                $datos_vivienda = [
                    'id' => $vivienda->getId_vivienda(),
                    'direccion' => $vivienda->getDireccion(),
                    'numero' => $vivienda->getNumero(),
                    'tipo' => $vivienda->getTipo(),
                    'sector' => $vivienda->getSector(),
                    'estado' => $vivienda->getEstado()
                ];
                return Response::response201('Vivienda creada exitosamente', $datos_vivienda);
            } else {
                return Response::response500('No se pudo crear la vivienda');
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
            $vivienda = new ViviendaModel();
            $vivienda->setId_vivienda($id);

            // Actualizar solo los campos proporcionados
            if (isset($datos['numero'])) $vivienda->setNumero($datos['numero']);
            if (isset($datos['tipo'])) $vivienda->setTipo($datos['tipo']);
            if (isset($datos['sector'])) $vivienda->setSector($datos['sector']);
            if (isset($datos['direccion'])) $vivienda->setDireccion($datos['direccion']);
            if (isset($datos['estado'])) $vivienda->setEstado($datos['estado']);

            if ($vivienda->actualizar()) {
                return Response::response200('Vivienda actualizada exitosamente');
            } else {
                return Response::response500('No se pudo actualizar la vivienda');
            }
        } catch (\Exception $e) {
            return Response::response500('Error al actualizar vivienda: ' . $e->getMessage());
        }
    }

    public function eliminar($id): array {
        try {
            $vivienda = new ViviendaModel();
            $vivienda->setId_vivienda($id);

            if ($vivienda->eliminar()) {
                return Response::response200('Vivienda eliminada exitosamente');
            } else {
                return Response::response500('No se pudo eliminar la vivienda');
            }
        } catch (\Exception $e) {
            return Response::response500('Error al eliminar vivienda: ' . $e->getMessage());
        }
    }

    public function contar($filtros = null): array {
        try {
            $vivienda = new ViviendaModel();
            $filtros = $filtros ?? [];
            $total = $vivienda->contar($filtros);

            return Response::response200('Conteo realizado exitosamente', ['total' => $total]);
        } catch (\Exception $e) {
            return Response::response500('Error al contar viviendas: ' . $e->getMessage());
        }
    }
}

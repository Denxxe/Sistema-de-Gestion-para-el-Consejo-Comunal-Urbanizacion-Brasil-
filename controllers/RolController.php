<?php
namespace App\controllers;

use App\models\RolModel;
use App\Core\Response;
class RolController {

    public function listar($filtros = null): array {
        try {
            $rol = new RolModel();
            $filtros = $filtros ?? [];
            $roles = $rol->listar($filtros);
            
            if (empty($roles)) {
                return Response::response404('No se encontraron roles');
            }
            
            return Response::response200('Roles listadas exitosamente', $roles);
        } catch (\Exception $e) {
            return Response::response500('Error al listar roles: ' . $e->getMessage());
        }
    }

    public function obtenerPorId($id): array {
        try {
            $rol = new RolModel();
            $rol->setId_rol($id);
            $rol = $rol->obtenerPorId($id);
            
            if (!$rol) {
                return Response::response404('Rol no encontrado');
            }
            
            return Response::response200('Rol obtenido exitosamente', $rol);
        } catch (\Exception $e) {
            return Response::response500('Error al obtener rol: ' . $e->getMessage());
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
        if (empty($datos['nombre']) || empty($datos['descripcion'])) {
            return Response::response400('Los campos nombre y descripcion son obligatorios');
        }

        try {
            $rol = new RolModel();

            $rol->setNombre($datos['nombre']);
            $rol->setDescripcion($datos['descripcion']);


            if ($rol->crear()) {
                $datos_rol = [
                    'id' => $rol->getId_Rol(), 
                    'nombre' => $rol->getNombre(),
                    'descripcion' => $rol->getDescripcion(),
                    'fecha_registro' => $rol->getFecha_registro()
                ];
                return Response::response201('Rol creado exitosamente', $datos_rol);
            } else {
                return Response::response500('No se pudo crear el rol');
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
            $rol = new RolModel();
            $rol->setId_Rol($id);

            // Actualizar solo los campos proporcionados
            if (isset($datos['nombre'])) $rol->setNombre($datos['nombre']);
            if (isset($datos['descripcion'])) $rol->setDescripcion($datos['descripcion']);

            if ($rol->actualizar()) {
                return Response::response200('Rol actualizado exitosamente');
            } else {
                return Response::response500('No se pudo actualizar el rol');
            }
        } catch (\Exception $e) {
            return Response::response500('Error al actualizar rol: ' . $e->getMessage());
        }
    }

    public function eliminar($id): array {
        try {
            $rol = new RolModel();
            $rol->setId_Rol($id);

            if ($rol->eliminar()) {
                return Response::response200('Rol eliminado exitosamente');
            } else {
                return Response::response500('No se pudo eliminar el rol');
            }
        } catch (\Exception $e) {
            return Response::response500('Error al eliminar rol: ' . $e->getMessage());
        }
    }

    public function contar($filtros = null): array {
        try {
            $rol = new RolModel();
            $filtros = $filtros ?? [];
            $total = $rol->contar($filtros);

            return Response::response200('Conteo realizado exitosamente', ['total' => $total]);
        } catch (\Exception $e) {
            return Response::response500('Error al contar roles: ' . $e->getMessage());
        }
    }
}

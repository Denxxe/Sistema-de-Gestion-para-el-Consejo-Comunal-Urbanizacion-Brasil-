<?php
namespace App\controllers;

use App\models\UsuarioModel;
use App\Core\Response;

class UsuarioController
{
    public function listar($filtros = null): array
    {
        try {
            $usuario = new UsuarioModel();
            $filtros = $filtros ?? [];
            $usuarios = $usuario->listar($filtros);
            
            if (empty($usuarios)) {
                return Response::response404('No se encontraron usuarios');
            }
            
            return Response::response200('Usuarios listados exitosamente', $usuarios);
        } catch (\Exception $e) {
            return Response::response500('Error al listar usuarios: ' . $e->getMessage());
        }
    }

    public function obtenerPorId($id): array
    {
        try {
            $usuario = new UsuarioModel();
            $usuario->setId_usuario($id);
            $usuarioData = $usuario->obtenerPorId($id);
            
            if (!$usuarioData) {
                return Response::response404('Usuario no encontrado');
            }
            
            return Response::response200('Usuario encontrado', $usuarioData);
        } catch (\Exception $e) {
            return Response::response500('Error al obtener usuario: ' . $e->getMessage());
        }
    }

    public function crear($datos = null): array
    {
        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $datos = json_decode(file_get_contents('php://input'), true);
        }

        if (!$datos) {
            return Response::response400('No se recibieron datos');
        }

        // Validación básica
        if (empty($datos['id_persona']) || empty($datos['id_rol']) || empty($datos['contrasena'])) {
            return Response::response400('Los campos id_persona, id_rol y contraseña son obligatorios');
        }

        try {
            $usuario = new UsuarioModel();
            
            $usuario->setId_persona($datos['id_persona']);
            $usuario->setId_rol($datos['id_rol']);
            $usuario->setContrasena($datos['contrasena']);
            $usuario->setEstado($datos['estado'] ?? null);

            if ($usuario->crear()) {
                $usuarioData = $usuario->obtenerPorId($usuario->getId_usuario());
                return Response::response201('Usuario creado exitosamente', $usuarioData);
            }
            
            return Response::response500('Error al crear usuario');
        } catch (\Exception $e) {
            return Response::response500('Error al crear usuario: ' . $e->getMessage());
        }
    }

    public function actualizar($id, $datos = null): array
    {
        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $datos = json_decode(file_get_contents('php://input'), true);
        }

        if (!$datos) {
            return Response::response400('No se recibieron datos');
        }

        try {
            $usuario = new UsuarioModel();
            $usuario->setId_usuario($id);

            if (empty($datos['id_persona']) || empty($datos['id_rol'])) {
                return Response::response400('Los campos id_persona e id_rol son obligatorios');
            }

            $usuario->setId_persona($datos['id_persona']);
            $usuario->setId_rol($datos['id_rol']);
            $usuario->setEstado($datos['estado'] ?? null);
            
            if (!empty($datos['contrasena'])) {
                $usuario->setContrasena($datos['contrasena']);
            }

            if ($usuario->actualizar()) {
                $usuarioData = $usuario->obtenerPorId($id);
                return Response::response200('Usuario actualizado exitosamente', $usuarioData);
            }
            
            return Response::response500('Error al actualizar usuario');
        } catch (\Exception $e) {
            return Response::response500('Error al actualizar usuario: ' . $e->getMessage());
        }
    }

    public function eliminar($id): array
    {
        try {
            $usuario = new UsuarioModel();
            $usuario->setId_usuario($id);
            
            if ($usuario->eliminar()) {
                return Response::response204('Usuario eliminado exitosamente');
            }
            
            return Response::response500('Error al eliminar usuario');
        } catch (\Exception $e) {
            return Response::response500('Error al eliminar usuario: ' . $e->getMessage());
        }
    }

    public function cambiarContrasena($id): array
    {
        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $datos = json_decode(file_get_contents('php://input'), true);
        }

        if (!$datos) {
            return Response::response400('No se recibieron datos');
        }

        try {
            if (empty($datos['contrasena_actual']) || empty($datos['contrasena_nueva'])) {
                return Response::response400('Faltan datos requeridos');
            }

            $usuario = new UsuarioModel();
            $usuario->setId_usuario($id);
            $usuarioData = $usuario->obtenerPorId($id);
            
            if (!$usuarioData || !password_verify($datos['contrasena_actual'], $usuarioData['contrasena'])) {
                return Response::response401('Contraseña actual incorrecta');
            }

            $usuario->setContrasena($datos['contrasena_nueva']);
            
            if ($usuario->actualizar()) {
                return Response::response200('Contraseña actualizada exitosamente');
            }
            
            return Response::response500('Error al actualizar contraseña');
        } catch (\Exception $e) {
            return Response::response500('Error al cambiar contraseña: ' . $e->getMessage());
        }
    }

    public function contar($filtros = null): array {
        try {
             $habitante = new UsuarioModel();
            $filtros = $filtros ?? [];
            $total =  $habitante->contar($filtros);

            return Response::response200('Conteo realizado exitosamente', ['total' => $total]);
        } catch (\Exception $e) {
            return Response::response500('Error al contar usuarios: ' . $e->getMessage());
        }
    }
}

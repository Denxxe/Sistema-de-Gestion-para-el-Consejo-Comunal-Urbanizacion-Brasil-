<?php
namespace App\controllers;

use App\models\UsuarioModel;
use App\models\HabitanteModel;
use App\Core\Response;
use App\Core\Database;

class UsuarioController {
    private $usuarioModel;
    private $habitanteModel;

    public function __construct() {
        $database = new Database();
        $this->usuarioModel = new UsuarioModel($database);
        $this->habitanteModel = new HabitanteModel($database);
    }

    public function listar($filtros = null): array {
        try {
            $filtros = $filtros ?? [];
            $usuarios = $this->usuarioModel->listar($filtros);
            return Response::response200('Usuarios listados exitosamente', $usuarios);
        } catch (\Exception $e) {
            return Response::response500('Error al listar usuarios: ' . $e->getMessage());
        }
    }

    public function obtenerPorId($id): array {
        try {
            $usuario = $this->usuarioModel->obtenerPorId($id);
            if ($usuario) {
                return Response::response200('Usuario encontrado', $usuario);
            } else {
                return Response::response404('Usuario no encontrado');
            }
        } catch (\Exception $e) {
            return Response::response500('Error al obtener usuario: ' . $e->getMessage());
        }
    }

    public function crear($datos = null): array {
        try {
            $datos = $this->getRequestData($datos);
            
            // Validar campos requeridos
            $camposRequeridos = ['id_habitante', 'id_rol', 'username', 'password', 'email'];
            foreach ($camposRequeridos as $campo) {
                if (empty($datos[$campo])) {
                    return Response::response400("El campo $campo es obligatorio");
                }
            }

            // Verificar si el habitante ya tiene un usuario
            $usuarioExistente = $this->usuarioModel->listar(['id_habitante' => $datos['id_habitante']]);
            if (!empty($usuarioExistente)) {
                return Response::response400('El habitante ya tiene un usuario asociado');
            }

            // Crear el usuario
            $this->usuarioModel->setIdHabitante($datos['id_habitante']);
            $this->usuarioModel->setIdRol($datos['id_rol']);
            $this->usuarioModel->setUsername($datos['username']);
            $this->usuarioModel->setPassword($datos['password']);
            $this->usuarioModel->setEmail($datos['email']);
            $this->usuarioModel->setEstado($datos['estado'] ?? 'ACTIVO');
            
            if (isset($datos['foto_perfil'])) {
                $this->usuarioModel->setFotoPerfil($datos['foto_perfil']);
            }

            if ($this->usuarioModel->crear()) {
                $usuarioCreado = $this->usuarioModel->obtenerPorId($this->usuarioModel->getIdUsuario());
                return Response::response201('Usuario creado exitosamente', $usuarioCreado);
            }

            return Response::response500('Error al crear el usuario');
            
        } catch (\Exception $e) {
            return Response::response500('Error al crear usuario: ' . $e->getMessage());
        }
    }

    public function actualizar($id, $datos = null): array {
        try {
            $datos = $this->getRequestData($datos);
            
            // Verificar si el usuario existe
            $usuarioExistente = $this->usuarioModel->obtenerPorId($id);
            if (!$usuarioExistente) {
                return Response::response404('Usuario no encontrado');
            }

            // Actualizar solo los campos proporcionados
            if (isset($datos['id_rol'])) $this->usuarioModel->setIdRol($datos['id_rol']);
            if (isset($datos['username'])) $this->usuarioModel->setUsername($datos['username']);
            if (isset($datos['email'])) $this->usuarioModel->setEmail($datos['email']);
            if (isset($datos['password'])) $this->usuarioModel->setPassword($datos['password']);
            if (isset($datos['foto_perfil'])) $this->usuarioModel->setFotoPerfil($datos['foto_perfil']);
            if (isset($datos['estado'])) $this->usuarioModel->setEstado($datos['estado']);
            
            $this->usuarioModel->setIdUsuario($id);

            if ($this->usuarioModel->actualizar()) {
                $usuarioActualizado = $this->usuarioModel->obtenerPorId($id);
                return Response::response200('Usuario actualizado exitosamente', $usuarioActualizado);
            }

            return Response::response500('Error al actualizar el usuario');
            
        } catch (\Exception $e) {
            return Response::response500('Error al actualizar usuario: ' . $e->getMessage());
        }
    }

    public function eliminar($id): array {
        try {
            // Verificar si el usuario existe
            $usuarioExistente = $this->usuarioModel->obtenerPorId($id);
            if (!$usuarioExistente) {
                return Response::response404('Usuario no encontrado');
            }

            $this->usuarioModel->setIdUsuario($id);
            
            if ($this->usuarioModel->eliminar()) {
                return Response::response200('Usuario eliminado exitosamente');
            }

            return Response::response500('Error al eliminar el usuario');
            
        } catch (\Exception $e) {
            return Response::response500('Error al eliminar usuario: ' . $e->getMessage());
        }
    }

    // Método auxiliar para obtener datos de la solicitud
    private function getRequestData($datos = null) {
        if ($datos === null) {
            if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
                $datos = json_decode(file_get_contents('php://input'), true);
            } else {
                $datos = $_POST;
                // Manejar archivos si es necesario
                if (isset($_FILES['foto_perfil'])) {
                    $datos['foto_perfil'] = $this->subirArchivo($_FILES['foto_perfil'], 'usuarios');
                }
            }
        }
        return $datos;
    }

    // Método para subir archivos
    private function subirArchivo($archivo, $directorio) {
        // Implementar lógica de subida de archivos
        // Retornar la ruta del archivo subido o null en caso de error
        return null; // Implementar según necesidades
    }
}
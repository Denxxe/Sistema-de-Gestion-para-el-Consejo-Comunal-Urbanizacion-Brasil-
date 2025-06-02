<?php
namespace App\controllers;

use App\Core\Response;

class PermisoController {
    public function index() {
        require_once __DIR__ . '/../view/permisos/index.php';
    }

    public function create() {
        require_once __DIR__ . '/../view/permisos/crear.php';
    }

    public function store() {
        // Lógica para guardar un nuevo permiso
        header('Location: /permisos');
        exit();
    }

    public function edit($id) {
        // Lógica para obtener el permiso a editar
        require_once __DIR__ . '/../view/permisos/editar.php';
    }

    public function update($id) {
        // Lógica para actualizar el permiso
        header('Location: /permisos');
        exit();
    }

    public function delete($id) {
        // Lógica para eliminar el permiso
        header('Location: /permisos');
        exit();
    }
}

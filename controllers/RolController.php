<?php
namespace App\controllers;

use App\Core\Response;

class RolController {
    public function index() {
        require_once __DIR__ . '/../view/roles/index.php';
    }

    public function create() {
        require_once __DIR__ . '/../view/roles/crear.php';
    }

    public function store() {
        // Lógica para guardar un nuevo rol
        header('Location: /roles');
        exit();
    }

    public function edit($id) {
        // Lógica para obtener el rol a editar
        require_once __DIR__ . '/../view/roles/editar.php';
    }

    public function update($id) {
        // Lógica para actualizar el rol
        header('Location: /roles');
        exit();
    }

    public function delete($id) {
        // Lógica para eliminar el rol
        header('Location: /roles');
        exit();
    }
}

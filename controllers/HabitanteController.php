<?php
namespace App\controllers;

use App\Core\Response;

class HabitanteController {
    public function index() {
        require_once __DIR__ . '/../view/habitantes/index.php';
    }

    public function create() {
        require_once __DIR__ . '/../view/habitantes/crear.php';
    }

    public function store() {
        // Lógica para guardar un nuevo habitante
        header('Location: /habitantes');
        exit();
    }

    public function edit($id) {
        // Lógica para obtener el habitante a editar
        require_once __DIR__ . '/../view/habitantes/editar.php';
    }

    public function update($id) {
        // Lógica para actualizar el habitante
        header('Location: /habitantes');
        exit();
    }

    public function delete($id) {
        // Lógica para eliminar el habitante
        header('Location: /habitantes');
        exit();
    }
}

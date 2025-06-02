<?php
namespace App\controllers;

use App\Core\Response;

class PagoController {
    public function index() {
        require_once __DIR__ . '/../view/pagos/index.php';
    }

    public function create() {
        require_once __DIR__ . '/../view/pagos/crear.php';
    }

    public function store() {
        // Lógica para guardar un nuevo pago
        header('Location: /pagos');
        exit();
    }

    public function edit($id) {
        // Lógica para obtener el pago a editar
        require_once __DIR__ . '/../view/pagos/editar.php';
    }

    public function update($id) {
        // Lógica para actualizar el pago
        header('Location: /pagos');
        exit();
    }

    public function delete($id) {
        // Lógica para eliminar el pago
        header('Location: /pagos');
        exit();
    }
}

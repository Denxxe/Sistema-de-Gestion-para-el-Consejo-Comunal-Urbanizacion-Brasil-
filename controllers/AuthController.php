<?php
namespace App\controllers;

use App\models\AuthModel;
use Exception;

class AuthController
{
    public function loginForm()
    {
        include '../view/auth/login.php';
    }

    public function login()
    {
        session_start();

        try {
            $cedula = $_POST['cedula'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($cedula) || empty($password)) {
                $_SESSION['error'] = 'Cédula y contraseña son requeridos';
                header('Location: /login');
                exit;
            }

            $authModel = new AuthModel();
            $user = $authModel->login($cedula, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_cedula'] = $user['cedula'];
                $_SESSION['user_nombres'] = $user['nombres'];
                $_SESSION['user_apellidos'] = $user['apellidos'];
                header('Location: /dashboard');
                exit;
            }

            $_SESSION['error'] = 'Cédula o contraseña incorrectos';
            header('Location: /login');
            exit;
        } catch (Exception $e) {
            error_log("Error en login: " . $e->getMessage());
            $_SESSION['error'] = 'Error al iniciar sesión';
            header('Location: /login');
            exit;
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /login');
    }
}

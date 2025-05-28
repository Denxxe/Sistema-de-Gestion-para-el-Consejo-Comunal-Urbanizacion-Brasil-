<?php
namespace App\Controllers;

use App\models\UserModel;
use App\Core\Database;

class AuthController
{
    public function loginForm()
    {
        include '../app/views/auth/login.php';
    }

    public function login()
    {
        session_start();

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $db = (new Database())->connect();
        $userModel = new UserModel();
        $userModel->setName(name: $email);
        $user = $userModel->getName();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            header('Location: /dashboard');
        } else {
            $_SESSION['error'] = 'Credenciales inválidas';
            header('Location: /login');
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /login');
    }
}

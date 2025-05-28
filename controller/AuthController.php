<?php

class AuthController extends BaseController
{
    public function loginForm()
    {
        $this->render('auth/login');
    }

    public function login()
    {
        // Aquí iría la lógica de autenticación
        if (isset($_POST['username']) && isset($_POST['password'])) {
            // Validar credenciales
            $_SESSION['user_id'] = 1; // Ejemplo simplificado
            $this->redirect('/dashboard');
        }
        $this->redirect('/login');
    }
}

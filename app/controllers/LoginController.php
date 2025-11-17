<?php

class LoginController extends Action
{

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $usuarioModel = new Usuario();
            $usuario = $usuarioModel->findByEmail($email);

            if (!$usuario || !password_verify($senha, $usuario['senha'])) {
                MessageService::setError("email ou senha inválidos.");
                return $this->redirect('/');
            }

            $_SESSION['user_id'] = $usuario['id'];

            return $this->redirect('/usuario');
        }

        $erro = MessageService::getError();
        $this->render('login/login', false, compact('erro'));
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/');
    }

}

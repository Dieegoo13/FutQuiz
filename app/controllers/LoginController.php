<?php

class LoginController extends Action
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            if (!LoginService::validarDados($email, $senha)) {
                MessageService::setError("Dados inválidos! Preencha os campos corretamente.");
                return $this->redirect('/');
            }

            $usuario = LoginService::validarUsuario($email, $senha);

            if (!$usuario) {
                MessageService::setError("E-mail ou senha incorretos!");
                return $this->redirect('/');
            }

            $_SESSION['user_id'] = $usuario['id'];

            return $this->redirect('/usuario');
        }

        $erro = MessageService::getError();

        $this->render('login/login', false, [
            'titulo' => 'Login',
            'estilos' => ['login.css'],
            'erro' => $erro
        ]);
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/');
    }
}

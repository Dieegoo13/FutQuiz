<?php
class UsuarioController extends Action{

    public function cadastro() {

        $erro = MessageService::getError();

        $this->render('cadastro/cadastro', true, [
            'titulo'=>'Cadastra-se',
            'estilos' => ['cadastro.css'],
            'erro' => $erro
        ]);
    }

    public function usuario()
    {
        $erro = MessageService::getError();

        $this->requireAuth(); // protege a rota
        
        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->findById($_SESSION['user_id']);

        $this->render('usuario/usuario', true, [
            'titulo' => 'Usuário',
            'estilos' => ['usuario.css'],
            'usuario' => $usuario,
            'erro' => $erro
        ]);
    }

    public function registrar(){

        $erro = MessageService::getError();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = $_POST['senha'];
            $confirm = $_POST['confirm'];

            $usuario = new Usuario();

            if($senha !== $confirm){
                $_SESSION['flash'] = "As senha devem ser iguais!";
                return $this->redirect('/cadastro');
            }

            if ($usuario->findByEmail($email)) {
                $_SESSION['flash_error'] = "Email já cadastrado!";
                return $this->redirect('/cadastro');
            }

            if ($usuario->criarUsuario($nome, $email, $senha)) {
                
                $_SESSION['flash_success'] = "Conta Criada com sucesso!";
                return $this->redirect('/');
            }

            $_SESSION['flash_error'] = "Erro ao criar conta, tente novamente!";
            return $this->redirect('/cadastro');
        }
            

        $this->render('cadastro/cadastro', true, [
            'titulo'=>'Cadastra-se',
            'estilos' => ['cadastro.css']
            
        ]);
    }

    public function atualizar(){

        session_start();

        $erro = MessageService::getError();

        if (!isset($_SESSION['user_id'])) {
            header("Location: /");
            exit;
        }

        $usuario = new Usuario();

        $id = $_SESSION['user_id'];
        $nome = $_POST['nome'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $confirm = $_POST['confirm'] ?? '';

        if ($senha === '') {
            $usuario->atualizarNome($id, $nome);
            $_SESSION['sucesso'] = "Dados atualizados com sucesso!";
            header("Location: /usuario");
            exit;
        }

        if ($senha !== $confirm) {
            $_SESSION['erro'] = "As senhas não conferem!";
            header("Location: /usuario");
            exit;
        }

        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $usuario->atualizarSenha($id, $hash);
        $usuario->atualizarNome($id, $nome);

        $_SESSION['sucesso'] = "Dados atualizados com sucesso!";
        header("Location: /usuario");

    }
}

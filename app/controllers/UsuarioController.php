<?php
class UsuarioController extends Action{

    public function cadastro() {

        $this->render('cadastro/cadastro', true, [
            'titulo'=>'Cadastra-se',
            'estilos' => ['cadastro.css']
            
        ]);
    }

    public function usuario() {


        $this->render('usuario/usuario', true, [
            'titulo'=>'Usuário',
            'estilos' => ['usuario.css']
        ]);
        
    }

    public function registrar(){

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
}

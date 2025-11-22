<?php
class UsuarioController extends Action
{
    // ============================
    //  CADASTRO (exibe página)
    // ============================
   public function cadastro()
    {
        $erro = MessageService::getError();
        $sucesso = MessageService::getSuccess();

        // Determina o tipo
        $feedback = $erro ?: $sucesso;
        $tipo = $erro ? "erro" : ($sucesso ? "success" : "");

        $this->render('cadastro/cadastro', true, [
            'titulo' => 'Cadastra-se',
            'estilos' => ['cadastro.css'],
            'feedback' => $feedback,
            'tipo' => $tipo
        ]);
    }
    // ============================
    //  REGISTRAR (POST)
    // ============================
    public function registrar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/cadastro');
        }

        $nome = $this->sanitize($_POST['nome'] ?? '');
        $email = $this->sanitize($_POST['email'] ?? '');
        $senha = $this->sanitize($_POST['senha'] ?? '');
        $confirm = $this->sanitize($_POST['confirm'] ?? '');

        $usuario = new Usuario();

        // 1 — Validar senha
        if (!LoginService::validarSenha($senha, $confirm)) {
            return $this->redirect('/cadastro');
        }

        // 2 — Verificar email duplicado
        if ($usuario->findByEmail($email)) {
            MessageService::setError("E-mail já cadastrado!");
            return $this->redirect('/cadastro');
        }

        // 3 — Criar usuário
        if ($usuario->criarUsuario($nome, $email, $senha)) {
            MessageService::setSuccess("Conta criada com sucesso!");
            return $this->redirect('/');
        }

        // 4 — Erro genérico
        MessageService::setError("Erro ao criar conta. Tente novamente!");
        return $this->redirect('/cadastro');
    }

    // ============================
    //  PERFIL USUÁRIO
    // ============================
    public function usuario()
    {
        $this->requireAuth();

        $erro = MessageService::getError();
        $sucesso = MessageService::getSuccess();

        $feedback = $erro ?: $sucesso;
        $tipo = $erro ? "erro" : ($sucesso ? "sucesso" : "");

        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->findById($_SESSION['user_id']);

        $this->render('usuario/usuario', true, [
            'titulo' => 'Usuário',
            'estilos' => ['usuario.css'],
            'usuario' => $usuario,
            'feedback' => $feedback,
            'tipo' => $tipo
        ]);
    }

    // ============================
    //  ATUALIZAR PERFIL
    // ============================
    public function atualizar()
    {
        $this->requireAuth();

        $usuario = new Usuario();
        $id = $_SESSION['user_id'];

        $nome = trim($_POST['nome'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $confirm = $_POST['confirm'] ?? '';

        // Alterar só o nome
        if ($senha === '') {
            $usuario->atualizarNome($id, $nome);
            MessageService::setSuccess("Dados atualizados com sucesso!");
            return $this->redirect('/usuario');
        }

        // Validar senha
        if ($senha !== $confirm) {
            MessageService::setError("As senhas não conferem!");
            return $this->redirect('/usuario');
        }
        

        // Atualiza tudo
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $usuario->atualizarSenha($id, $hash);
        $usuario->atualizarNome($id, $nome);

        MessageService::setSuccess("Dados atualizados com sucesso!");
        return $this->redirect('/usuario');
    }
}

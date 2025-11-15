<?php
class UsuarioController extends Action{

    protected $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function login() {
        $this->render('login/login', false);
        echo "<h1>Página de Login</h1>";
    }

    public function cadastro() {
        $this->render('cadastro/cadastro', false);
        echo "<h1>Página de Cadastro</h1>";
    }

    public function usuario() {
        $this->render('usuario/usuario', false);
        echo "<h1>Página do Usuário</h1>";
    }
}

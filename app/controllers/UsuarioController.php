<?php
class UsuarioController extends Action{

    public function cadastro() {
        $this->render('cadastro/cadastro', false);
        echo "<h1>Página de Cadastro</h1>";
    }

    public function usuario() {
        $this->render('usuario/usuario', false);
        echo "<h1>Página do Usuário</h1>";
    }
}

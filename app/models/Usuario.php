<?php

class Usuario
{

    private $db;
    private $table = 'tb_usuarios';

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function criarUsuario($nome, $email, $senha)
    {
        $query = "INSERT INTO {$this->table} (nome_usuario, email, senha, token_login) 
                VALUES (:nome_usuario, :email, :senha, :token_login)";

        try {
            $stmt = $this->db->prepare($query);
            $hashedPassword = password_hash($senha, PASSWORD_DEFAULT);
            $token = TokenService::generate(); 

            $stmt->execute([
                ':nome_usuario' => $nome,
                ':email' => $email,
                ':senha' => $hashedPassword,
                ':token_login' => $token
            ]);

            return true;
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }


    public function findByEmail($email)
    {

        $query = "SELECT id, senha FROM {$this->table} 
                    WHERE email = :email LIMIT 1";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([':email' => $email]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }
}

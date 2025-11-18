<?php

class Usuario
{

    private $conn;
    private $table = 'tb_usuarios';

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }


    public function findById($id){
        $query = "SELECT id, nome_usuario, email, pontos_totais, ranking
                  FROM  {$this->table}
                  WHERE id = :id
                  LIMIT 1";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }catch(\PDOException $e){
            echo $e->getMessage();
            exit;
        }          
    }

    public function criarUsuario($nome, $email, $senha)
    {
        $query = "INSERT INTO {$this->table} (nome_usuario, email, senha, token_login) 
                VALUES (:nome_usuario, :email, :senha, :token_login)";

        try {
            $stmt = $this->conn->prepare($query);
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
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':email' => $email]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function atualizarNome($id, $nome){
        
        $query = "UPDATE {$this->table} set nome_usuario = :nome
                  WHERE id = :id ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":nome", $nome);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function atualizarSenha($id, $senha)
    {
        $query = "UPDATE {$this->table} SET senha = :senha 
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":senha", $senha);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

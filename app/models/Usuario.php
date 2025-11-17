<?php

use Database;

class Usuario
{

    private $db;
    private $table = 'tb_usuarios';

    public function __construct()
    {
        $this->db = Database::getConnection();
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
            return false;
        }
    }
}

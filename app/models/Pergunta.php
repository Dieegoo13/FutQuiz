<?php

class Pergunta
{

    private $conn;
    private $table = 'tb_perguntas';

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }


    public function sortearPerguntas($limite = 10){
            
        $query = "SELECT id, enunciado, alternativa_a, alternativa_b, alternativa_c, alternativa_d
                FROM {$this->table}
                ORDER BY RAND()
                LIMIT :limite";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":limite", $limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

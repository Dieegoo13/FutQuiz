<?php
class QuizController extends Action{
    
    public function quiz() {
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }

        $this->render('quiz/quiz', false);
    }


    public function apiPerguntas()
    {
        header("Content-Type: application/json; charset=utf-8");

        $pergunta = new Pergunta();
        $perguntas = $pergunta->sortearPerguntas(10);

        echo json_encode($perguntas, JSON_UNESCAPED_UNICODE);
    }


    public function resultado() {
        $this->render('resultado/resultado', false);
        echo "<h1>Página de Resultado</h1>";
    }

}

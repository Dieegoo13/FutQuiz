<?php
class QuizController extends Action{
    
    public function quiz() {
        $this->render('quiz/quiz', false);
    }

    public function resultado() {
        $this->render('resultado/resultado', false);
        echo "<h1>Página de Resultado</h1>";
    }

}

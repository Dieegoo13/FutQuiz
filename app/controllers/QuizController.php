<?php
class QuizController extends Action{
    
    public function quiz() {
        $this->render('quiz/quiz', false);
        echo "<h1>Página de Quiz</h1>";
    }

    public function resultado() {
        $this->render('resultado/resultado', false);
        echo "<h1>Página de Resultado</h1>";
    }

}

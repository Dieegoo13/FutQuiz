<?php
class QuizController extends Action
{

    public function quiz()
    {

        $this->requireAuth();

        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }

        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->findById($_SESSION['user_id']);

        $this->render(
            'quiz/quiz',
            false,
            [
                'usuario' => $usuario,
            ]
        );
    }


    public function apiPerguntas()
    {
        header("Content-Type: application/json; charset=utf-8");

        $pergunta = new Pergunta();
        $perguntas = $pergunta->sortearPerguntas(10);

        echo json_encode($perguntas, JSON_UNESCAPED_UNICODE);
    }


    public function resultado() {

        $this->requireAuth();

        $usuario = new Usuario();

        // Se vier um POST (final do jogo)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = json_decode(file_get_contents("php://input"), true);

            $acertos = $data['acertos'] ?? 0;
            $pontosGanhos = $data['pontosGanhos'] ?? 0;


            $dadosUsuario = $usuario->findById($_SESSION['user_id']);
            $pontuacaoAtual = $dadosUsuario['pontuacao'] ?? 0;

 
            $usuario->atualizarPontuacao($_SESSION['user_id'], $pontosGanhos);

            $_SESSION['resultado_final'] = [
                "acertos" => $acertos,
                "pontosGanhos" => $pontosGanhos,
                "pontuacaoTotal" => $pontuacaoAtual + $pontosGanhos
            ];

            echo json_encode(["ok" => true]);
            exit;
        }

        if (!isset($_SESSION['resultado_final'])) {
            header("Location: /quiz");
            exit;
        }

        $dados = $_SESSION['resultado_final'];
        unset($_SESSION['resultado_final']);

        $this->render("resultado/resultado", true, [
            "acertos" => $dados["acertos"],
            "pontosGanhos" => $dados["pontosGanhos"],
            "pontuacaoTotal" => $dados["pontuacaoTotal"],
            "estilos" => ["resultado.css"],
            "titulo" => "Resultado"
        ]);
    }

}

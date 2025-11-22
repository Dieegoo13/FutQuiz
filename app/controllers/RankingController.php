<?php
class RankingController extends Action
{

    public function ranking()
    {

        $usuario = new Usuario();
        $top5 = $usuario->topCinco();


        $this->render('ranking/ranking', true, [
            "titulo" => "Ranking",
            "estilos" => ["ranking.css"],
            "top5" => $top5
        ]);
    }
}

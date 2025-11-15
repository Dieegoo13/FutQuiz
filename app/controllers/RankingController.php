<?php
class RankingController extends Action{
    
    public function ranking() {
        $this->render('ranking/ranking', false);
        echo "<h1>Página de ranking</h1>";
    }

}

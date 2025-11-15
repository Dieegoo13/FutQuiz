<?php

class Action
{
    protected $layout = 'default'; 

    protected function render($view, $layout = true, $data = [])
    {   

        extract($data);

        $viewPath = __DIR__ . '/../views' .  '/' . $view . '.phtml';

        if (!file_exists($viewPath)) {
            die(" Ops! View não encontrada: {$viewPath}");
        }

        ob_start();
        require_once $viewPath;
        $content = ob_get_clean();

        // se $layout for false, mostra só o conteúdo
        if ($layout === false) {
            echo $content;
            return;
        }

        //  FALTANDO: O código do layout não está sendo executado!
        $layoutFile = $viewPath . '/layouts/' . $this->layout . '.phtml';

        if (file_exists($layoutFile)) {
            include $layoutFile;
        } else {
            echo $content;
        }
    
    }

    protected function redirect($path){
        header('Location: ' . BASE_URL . ltrim($path, '/'));
        exit;
    }

    protected function json($data){
        http_response_code(200);

        header('Content-Type: application/json');
        echo json_decode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }


    //TO DO FAZER AUTENTICAÇÕES 


    //TO DO VALIDAR EMAILS E LOGINS

    //TO DO SANITIZAR INPUTS
}

<?php

class Router
{
    private $routes = [];

    public function __construct()
    {
        $this->initRoutes();
        $this->dispatch();
    }

    /**
     * Define as rotas do projeto
     */
    protected function initRoutes()
    {
        $this->routes = [
            '/' => [
                'controller' => 'LoginController',
                'action' => 'login'
            ],

            '/logout' => [
                'controller' => 'LoginController',
                'action' => 'logout'
            ],

            '/cadastro' => [
                'controller' => 'UsuarioController',
                'action' => 'cadastro'
            ],

            '/usuario' => [
                'controller' => 'UsuarioController',
                'action' => 'usuario'
            ],

            '/quiz' => [
                'controller' => 'QuizController',
                'action' => 'quiz'
            ],

            '/resultado' => [
                'controller' => 'QuizController',
                'action' => 'resultado'
            ],

            '/ranking' => [
                'controller' => 'RankingController',
                'action' => 'ranking'
            ],

            '/registrar' => [
                'controller' => 'UsuarioController',
                'action' => 'registrar'
            ]
            
        ];
    }

    /**
     * Retorna a rota atual (sem query string)
     */
    private function getCurrentPath()
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $base = str_replace('/public', '', dirname($_SERVER['SCRIPT_NAME']));

        // Remove o prefixo do caminho base (pra funcionar em InfinityFree)
        $path = str_replace($base, '', $path);

        return rtrim($path, '/') ?: '/';
    }

    /**
     * Executa o roteamento
     */
    private function dispatch()
    {
        $path = $this->getCurrentPath();

        if (!isset($this->routes[$path])) {
            http_response_code(404);
            echo "<h1>404 - Página não encontrada</h1>";
            return;
        }

        $route = $this->routes[$path];
        $controllerName = $route['controller'];
        $action = $route['action'];

        $controllerFile = __DIR__ . '/../../app/controllers/' . $controllerName . '.php';
        if (!file_exists($controllerFile)) {
            die("<h1>Erro: Controller '{$controllerName}' não encontrado.</h1>");
        }

        require_once $controllerFile;

        if (!class_exists($controllerName)) {
            die("<h1>Erro: Classe '{$controllerName}' não declarada.</h1>");
        }

        $controller = new $controllerName;

        if (!method_exists($controller, $action)) {
            die("<h1>Erro: Método '{$action}' não encontrado no controller '{$controllerName}'.</h1>");
        }

        // Chama o método
        call_user_func([$controller, $action]);
    }
}

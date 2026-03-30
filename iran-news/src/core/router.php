<?php
/**
 * Classe Router - Gestion du routage des URLs
 */

class Router {
    private $routes = [];
    private $currentController = null;
    private $currentAction = null;
    private $params = [];

    /**
     * Définit une route
     */
    public function addRoute($pattern, $controller, $action) {
        $this->routes[] = [
            'pattern' => $pattern,
            'controller' => $controller,
            'action' => $action
        ];
    }

    /**
     * Analyse l'URL et retourne le contrôleur et l'action
     */
    public function dispatch() {
        $url = $this->getUrl();
        
        // Route accueil
        if ($url === '' || $url === '/') {
            $this->currentController = 'home';
            $this->currentAction = 'index';
            $this->params = [];
            return;
        }

        // Supprime le slash au début et à la fin
        $url = trim($url, '/');
        $parts = explode('/', $url);

        // Route /article/slug-article
        if ($parts[0] === 'article' && count($parts) === 2) {
            $this->currentController = 'article';
            $this->currentAction = 'detail';
            $this->params = ['slug' => $parts[1]];
            return;
        }

        // Route /category/nom-categorie
        if ($parts[0] === 'category' && count($parts) === 2) {
            $this->currentController = 'category';
            $this->currentAction = 'list';
            $this->params = ['slug' => $parts[1]];
            return;
        }

        // Route /admin
        if ($parts[0] === 'admin') {
            Session::start();
            
            // Actions publiques (pas besoin d'être connecté)
            if (isset($parts[1]) && in_array($parts[1], ['login', 'logout'])) {
                $this->currentController = 'auth';
                $this->currentAction = $parts[1];
                return;
            }

            // Autres actions admin (nécessitent l'authentification)
            $this->currentController = 'admin';
            $this->currentAction = isset($parts[1]) ? $parts[1] : 'dashboard';
            
            // Paramètres supplémentaires
            if (count($parts) > 2) {
                $this->params['id'] = $parts[2];
            }
            return;
        }

        // Pas de route trouvée
        $this->currentController = 'home';
        $this->currentAction = 'notfound';
        $this->params = [];
    }

    /**
     * Récupère l'URL actuelle
     */
    private function getUrl() {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    /**
     * Retourne le contrôleur actuel
     */
    public function getController() {
        return $this->currentController;
    }

    /**
     * Retourne l'action actuelle
     */
    public function getAction() {
        return $this->currentAction;
    }

    /**
     * Retourne les paramètres
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * Récupère un paramètre spécifique
     */
    public function getParam($key, $default = null) {
        return $this->params[$key] ?? $default;
    }

    /**
     * Charge le contrôleur et exécute l'action
     */
    public function execute() {
        $controller_file = __DIR__ . '/../controllers/' . $this->currentController . '.php';

        if (!file_exists($controller_file)) {
            die('Contrôleur non trouvé: ' . $controller_file);
        }

        require_once $controller_file;

        $controller_class = ucfirst($this->currentController) . 'Controller';
        
        if (!class_exists($controller_class)) {
            die('Classe contrôleur non trouvée: ' . $controller_class);
        }

        $controller = new $controller_class();
        $action = $this->currentAction . 'Action';

        if (!method_exists($controller, $action)) {
            die('Action non trouvée: ' . $action);
        }

        // Exécute l'action avec les paramètres
        $controller->$action($this->params);
    }
}

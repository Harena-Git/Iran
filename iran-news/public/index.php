<?php
/**
 * Point d'entrée principal de l'application
 * Toutes les requêtes passent par ce fichier
 */

// Démarrage de la session
session_start();

// Chemins absolus
define('ROOT_PATH', dirname(__DIR__));
define('SRC_PATH', ROOT_PATH . '/src');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('TEMPLATES_PATH', ROOT_PATH . '/templates');

// Autoload des classes
require_once SRC_PATH . '/core/database.php';
require_once SRC_PATH . '/core/session.php';
require_once SRC_PATH . '/core/router.php';

// Autoload des modèles
$models_dir = SRC_PATH . '/models/';
foreach (glob($models_dir . '*.php') as $file) {
    require_once $file;
}

// Instanciation du router
$router = new Router();
$router->dispatch();

// Exécution du contrôleur et de l'action
try {
    $router->execute();
} catch (Exception $e) {
    http_response_code(500);
    echo 'Erreur: ' . $e->getMessage();
}

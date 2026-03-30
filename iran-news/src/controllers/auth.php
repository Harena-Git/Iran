<?php
/**
 * Contrôleur Auth - Authentification des utilisateurs
 */

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    /**
     * Action : Formulaire et traitement du login
     */
    public function loginAction($params) {
        Session::start();

        // Si déjà connecté, rediriger vers le tableau de bord
        if (Session::isLoggedIn()) {
            header('Location: /admin');
            exit;
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!$email || !$password) {
                $error = 'Email et mot de passe requis';
            } else {
                // Validation du format email
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Format email invalide';
                } else {
                    $user = $this->userModel->authenticate($email, $password);

                    if ($user) {
                        Session::login($user['id'], $user['username'], $user['email']);
                        header('Location: /admin');
                        exit;
                    } else {
                        $error = 'Email ou mot de passe incorrect';
                    }
                }
            }
        }

        $data = [
            'title' => 'Connexion - Iran News',
            'error' => $error,
            'success' => $success
        ];

        $this->render('admin/login', $data);
    }

    /**
     * Action : Déconnexion
     */
    public function logoutAction($params) {
        Session::start();
        Session::logout();
        
        header('Location: /');
        exit;
    }

    /**
     * Affiche une vue
     */
    private function render($view, $data = []) {
        extract($data);
        include TEMPLATES_PATH . '/' . $view . '.php';
    }
}

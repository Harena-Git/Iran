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
     * Action : Formulaire et traitement de l'inscription
     */
    public function registerAction($params) {
        Session::start();

        // Si déjà connecté, rediriger vers le tableau de bord
        if (Session::isLoggedIn()) {
            header('Location: /admin');
            exit;
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';

            // Validation
            if (!$username || !$email || !$password) {
                $error = 'Tous les champs sont requis';
            } elseif (strlen($username) < 3) {
                $error = 'Le nom d\'utilisateur doit faire au moins 3 caractères';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Format email invalide';
            } elseif ($password !== $password_confirm) {
                $error = 'Les mots de passe ne correspondent pas';
            } elseif (strlen($password) < 6) {
                $error = 'Le mot de passe doit faire au moins 6 caractères';
            } else {
                // Vérifier si l'email existe déjà
                if ($this->userModel->getByEmail($email)) {
                    $error = 'Cet email est déjà utilisé';
                } elseif ($this->userModel->getByUsername($username)) {
                    $error = 'Ce nom d\'utilisateur est déjà utilisé';
                } else {
                    // Créer l'utilisateur
                    $user = $this->userModel->create($username, $email, $password, 'editor');
                    if ($user) {
                        $success = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
                    } else {
                        $error = 'Erreur lors de l\'inscription';
                    }
                }
            }
        }

        $data = [
            'title' => 'Inscription - Iran News',
            'error' => $error,
            'success' => $success
        ];

        $this->render('admin/register', $data);
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

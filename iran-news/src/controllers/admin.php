<?php
/**
 * Contrôleur Admin - Backoffice avec authentification
 */

class AdminController {
    private $userModel;
    private $articleModel;
    private $categoryModel;

    public function __construct() {
        $this->userModel = new User();
        $this->articleModel = new Article();
        $this->categoryModel = new Category();

        // Vérifie que l'utilisateur est connecté
        if (!Session::isLoggedIn()) {
            header('Location: /admin/login');
            exit;
        }
    }

    /**
     * Action : Tableau de bord
     */
    public function dashboardAction($params) {
        $user = Session::getUser();
        $data = [
            'title' => 'Tableau de bord',
            'user' => $user
        ];
        $this->render('admin/dashboard', $data);
    }

    /**
     * Action : Liste des articles
     */
    public function articlesAction($params) {
        $user = Session::getUser();
        $articles = $this->articleModel->getDraftsBy($user['id']);

        $data = [
            'title' => 'Mes articles',
            'articles' => $articles,
            'user' => $user
        ];
        $this->render('admin/articles', $data);
    }

    /**
     * Action : Créer/Éditer un article
     */
    public function editarticleAction($params) {
        $user = Session::getUser();
        $article = null;
        
        if (isset($params['id'])) {
            $article = $this->articleModel->getById($params['id']);
            if (!$article || $article['author_id'] !== $user['id']) {
                die('Accès refusé');
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Session::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                die('Token CSRF invalide');
            }

            // Filtrage et sanitization des entrées
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $excerpt = trim($_POST['excerpt'] ?? '');
            $categoryId = intval($_POST['category_id'] ?? 0);
            $status = in_array($_POST['status'] ?? 'draft', ['draft', 'published']) ? $_POST['status'] : 'draft';

            if (!$title || !$content) {
                die('Le titre et le contenu sont obligatoires');
            }

            if ($article) {
                // Mise à jour
                $this->articleModel->update($article['id'], [
                    'title' => $title,
                    'content' => $content,
                    'excerpt' => $excerpt,
                    'category_id' => $categoryId,
                    'status' => $status
                ]);
            } else {
                // Création
                $this->articleModel->create($title, $content, $excerpt, $categoryId, $user['id'], $status);
            }

            header('Location: /admin/articles');
            exit;
        }

        $categories = $this->categoryModel->getAll();
        $csrfToken = Session::generateCSRFToken();

        $data = [
            'title' => $article ? 'Éditer l\'article' : 'Créer un article',
            'article' => $article,
            'categories' => $categories,
            'csrf_token' => $csrfToken
        ];
        $this->render('admin/edit_article', $data);
    }

    /**
     * Action : Créer un utilisateur
     */
    public function adduserAction($params) {
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Session::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                die('Token CSRF invalide');
            }

            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = in_array($_POST['role'] ?? 'editor', ['admin', 'editor']) ? $_POST['role'] : 'editor';

            if (!$username || !$email || !$password) {
                $error = 'Tous les champs sont obligatoires';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Format email invalide';
            } elseif (strlen($password) < 6) {
                $error = 'Le mot de passe doit faire au moins 6 caractères';
            } else {
                $result = $this->userModel->create($username, $email, $password, $role);
                if ($result) {
                    $success = 'Utilisateur créé avec succès';
                } else {
                    $error = 'Erreur lors de la création (username ou email déjà existant)';
                }
            }
        }

        $csrfToken = Session::generateCSRFToken();

        $data = [
            'title' => 'Ajouter un utilisateur',
            'csrf_token' => $csrfToken,
            'error' => $error,
            'success' => $success
        ];
        $this->render('admin/add_user', $data);
    }

    /**
     * Affiche une vue
     */
    private function render($view, $data = []) {
        extract($data);
        include TEMPLATES_PATH . '/' . $view . '.php';
    }
}

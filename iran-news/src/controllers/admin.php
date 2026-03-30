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
     * Affiche une vue
     */
    private function render($view, $data = []) {
        extract($data);
        include TEMPLATES_PATH . '/' . $view . '.php';
    }
}

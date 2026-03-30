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

    // ============================
    // GESTION DES ARTICLES
    // ============================

    /**
     * Action : Liste de tous les articles
     */
    public function articlesAction($params) {
        $user = Session::getUser();
        $articles = $this->articleModel->getAll();

        $data = [
            'title' => 'Tous les articles',
            'articles' => $articles,
            'user' => $user
        ];
        $this->render('admin/articles', $data);
    }

    /**
     * Action : Créer/Éditer un article (avec TinyMCE + upload images)
     */
    public function editarticleAction($params) {
        $user = Session::getUser();
        $article = null;
        $articleImages = [];
        
        if (isset($params['id'])) {
            $article = $this->articleModel->getById($params['id']);
            if (!$article) {
                die('Article non trouvé');
            }
            $articleImages = $this->articleModel->getImages($article['id']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Session::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                die('Token CSRF invalide');
            }

            $title = trim($_POST['title'] ?? '');
            $content = $_POST['content'] ?? ''; // Ne pas trim le HTML de TinyMCE
            $excerpt = trim($_POST['excerpt'] ?? '');
            $categoryId = intval($_POST['category_id'] ?? 0);
            $status = in_array($_POST['status'] ?? 'draft', ['draft', 'published']) ? $_POST['status'] : 'draft';

            if (!$title || !$content) {
                die('Le titre et le contenu sont obligatoires');
            }

            $articleId = null;

            if ($article) {
                // Mise à jour
                $publishedAt = null;
                if ($status === 'published' && $article['status'] !== 'published') {
                    $publishedAt = date('Y-m-d H:i:s');
                }
                $updateData = [
                    'title' => $title,
                    'content' => $content,
                    'excerpt' => $excerpt,
                    'category_id' => $categoryId,
                    'status' => $status
                ];
                if ($publishedAt) {
                    $updateData['published_at'] = $publishedAt;
                }
                $this->articleModel->update($article['id'], $updateData);
                $articleId = $article['id'];
            } else {
                // Création
                $result = $this->articleModel->create($title, $content, $excerpt, $categoryId, $user['id'], $status);
                if ($result) {
                    $articleId = $result['id'];
                }
            }

            // Upload des images
            if ($articleId && !empty($_FILES['images']['name'][0])) {
                $this->handleImageUpload($articleId, $_FILES['images']);
            }

            header('Location: /admin/articles');
            exit;
        }

        $categories = $this->categoryModel->getAll();
        $csrfToken = Session::generateCSRFToken();

        $data = [
            'title' => $article ? 'Éditer l\'article' : 'Créer un article',
            'article' => $article,
            'articleImages' => $articleImages,
            'categories' => $categories,
            'csrf_token' => $csrfToken
        ];
        $this->render('admin/edit_article', $data);
    }

    /**
     * Action : Supprimer un article
     */
    public function deletearticleAction($params) {
        if (!isset($params['id'])) {
            die('ID article manquant');
        }

        $article = $this->articleModel->getById($params['id']);
        if (!$article) {
            die('Article non trouvé');
        }

        // Supprimer les images associées du disque
        $images = $this->articleModel->getImages($article['id']);
        foreach ($images as $img) {
            $filePath = ROOT_PATH . '/uploads/' . basename($img['url']);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $this->articleModel->delete($article['id']);
        header('Location: /admin/articles');
        exit;
    }

    /**
     * Gère l'upload multiple d'images pour un article
     */
    private function handleImageUpload($articleId, $files) {
        $uploadDir = ROOT_PATH . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5 MB

        $db = Database::getInstance()->getConnection();

        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
            if (!in_array($files['type'][$i], $allowedTypes)) continue;
            if ($files['size'][$i] > $maxSize) continue;

            $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
            $filename = uniqid('img_') . '.' . $ext;
            $destination = $uploadDir . $filename;

            if (move_uploaded_file($files['tmp_name'][$i], $destination)) {
                $url = '/uploads/' . $filename;
                $alt = pathinfo($files['name'][$i], PATHINFO_FILENAME);

                $stmt = $db->prepare('INSERT INTO images (article_id, url, alt) VALUES (:article_id, :url, :alt)');
                $stmt->execute([
                    ':article_id' => $articleId,
                    ':url' => $url,
                    ':alt' => $alt
                ]);
            }
        }
    }

    // ============================
    // GESTION DES CATÉGORIES
    // ============================

    /**
     * Action : Liste des catégories
     */
    public function categoriesAction($params) {
        $categories = $this->categoryModel->getAll();
        $data = [
            'title' => 'Gestion des catégories',
            'categories' => $categories
        ];
        $this->render('admin/categories', $data);
    }

    /**
     * Action : Ajouter une catégorie
     */
    public function addcategoryAction($params) {
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Session::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                die('Token CSRF invalide');
            }

            $name = trim($_POST['name'] ?? '');

            if (!$name) {
                $error = 'Le nom de la catégorie est obligatoire';
            } else {
                $result = $this->categoryModel->create($name);
                if ($result) {
                    header('Location: /admin/categories');
                    exit;
                } else {
                    $error = 'Erreur lors de la création (nom ou slug déjà existant)';
                }
            }
        }

        $csrfToken = Session::generateCSRFToken();
        $data = [
            'title' => 'Ajouter une catégorie',
            'csrf_token' => $csrfToken,
            'error' => $error,
            'success' => $success
        ];
        $this->render('admin/add_category', $data);
    }

    /**
     * Action : Modifier une catégorie
     */
    public function editcategoryAction($params) {
        if (!isset($params['id'])) {
            die('ID catégorie manquant');
        }

        $category = $this->categoryModel->getById($params['id']);
        if (!$category) {
            die('Catégorie non trouvée');
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Session::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                die('Token CSRF invalide');
            }

            $name = trim($_POST['name'] ?? '');

            if (!$name) {
                $error = 'Le nom de la catégorie est obligatoire';
            } else {
                $this->categoryModel->update($category['id'], $name);
                header('Location: /admin/categories');
                exit;
            }
        }

        $csrfToken = Session::generateCSRFToken();
        $data = [
            'title' => 'Modifier la catégorie',
            'category' => $category,
            'csrf_token' => $csrfToken,
            'error' => $error
        ];
        $this->render('admin/edit_category', $data);
    }

    /**
     * Action : Supprimer une catégorie
     */
    public function deletecategoryAction($params) {
        if (!isset($params['id'])) {
            die('ID catégorie manquant');
        }

        $this->categoryModel->delete($params['id']);
        header('Location: /admin/categories');
        exit;
    }

    // ============================
    // GESTION DES UTILISATEURS
    // ============================

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


<?php
/**
 * Contrôleur Category - Liste des articles par catégorie
 */

class CategoryController {
    private $categoryModel;
    private $articleModel;

    public function __construct() {
        $this->categoryModel = new Category();
        $this->articleModel = new Article();
    }

    /**
     * Action : Affiche les articles d'une catégorie
     */
    public function listAction($params) {
        $slug = $params['slug'] ?? null;

        if (!$slug) {
            http_response_code(404);
            die('Catégorie non trouvée');
        }

        $category = $this->categoryModel->getBySlug($slug);

        if (!$category) {
            http_response_code(404);
            die('Catégorie non trouvée');
        }

        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $articles = $this->categoryModel->getArticles($category['id'], $limit, $offset);

        $data = [
            'title' => 'Catégorie: ' . $category['name'] . ' - Iran News',
            'description' => 'Articles de la catégorie ' . $category['name'],
            'category' => $category,
            'articles' => $articles,
            'currentPage' => $page
        ];

        $this->render('front/category', $data);
    }

    /**
     * Affiche une vue
     */
    private function render($view, $data = []) {
        extract($data);
        include TEMPLATES_PATH . '/' . $view . '.php';
    }
}

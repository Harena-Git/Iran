<?php
/**
 * Contrôleur Home - Page d'accueil et articles
 */

class HomeController {
    private $articleModel;
    private $categoryModel;

    public function __construct() {
        $this->articleModel = new Article();
        $this->categoryModel = new Category();
    }

    /**
     * Action : Affiche la page d'accueil
     */
    public function indexAction($params) {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $articles = $this->articleModel->getPublished($limit, $offset);
        $total = $this->articleModel->countPublished();
        $totalPages = ceil($total / $limit);
        $categories = $this->categoryModel->getAll();

        // Données pour la vue
        $data = [
            'title' => 'Iran News - Actualités',
            'description' => 'Site d\'actualités sur l\'Iran avec les dernières nouvelles',
            'articles' => $articles,
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total
        ];

        $this->render('front/home', $data);
    }

    /**
     * Action : Page 404
     */
    public function notfoundAction($params) {
        http_response_code(404);
        $data = ['title' => 'Page non trouvée'];
        $this->render('front/404', $data);
    }

    /**
     * Affiche une vue
     */
    private function render($view, $data = []) {
        extract($data);
        include TEMPLATES_PATH . '/' . $view . '.php';
    }
}

<?php
/**
 * Contrôleur Article - Détail des articles
 */

class ArticleController {
    private $articleModel;
    private $tagModel;

    public function __construct() {
        $this->articleModel = new Article();
        $this->tagModel = new Tag();
    }

    /**
     * Action : Affiche le détail d'un article
     */
    public function detailAction($params) {
        $slug = $params['slug'] ?? null;

        if (!$slug) {
            http_response_code(404);
            die('Article non trouvé');
        }

        $article = $this->articleModel->getBySlug($slug);

        if (!$article) {
            http_response_code(404);
            die('Article non trouvé');
        }

        $tags = $this->tagModel->getByArticle($article['id']);

        $data = [
            'title' => $article['title'] . ' - Iran News',
            'description' => $article['excerpt'],
            'article' => $article,
            'tags' => $tags
        ];

        $this->render('front/article', $data);
    }

    /**
     * Affiche une vue
     */
    private function render($view, $data = []) {
        extract($data);
        include TEMPLATES_PATH . '/' . $view . '.php';
    }
}

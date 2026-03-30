<?php
/**
 * Modèle Article - Gestion des articles
 */

class Article {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Crée un nouvel article
     */
    public function create($title, $content, $excerpt, $categoryId, $authorId, $status = 'draft') {
        $slug = $this->slugify($title);

        $stmt = $this->db->prepare('
            INSERT INTO articles (title, slug, content, excerpt, category_id, author_id, status, created_at, updated_at)
            VALUES (:title, :slug, :content, :excerpt, :category_id, :author_id, :status, NOW(), NOW())
            RETURNING id, title, slug
        ');

        return $stmt->execute([
            ':title' => $title,
            ':slug' => $slug,
            ':content' => $content,
            ':excerpt' => $excerpt,
            ':category_id' => $categoryId,
            ':author_id' => $authorId,
            ':status' => $status
        ]) ? $stmt->fetch() : false;
    }

    /**
     * Récupère un article par ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare('
            SELECT a.*, c.name as category_name, u.username as author_name
            FROM articles a
            LEFT JOIN categories c ON a.category_id = c.id
            LEFT JOIN users u ON a.author_id = u.id
            WHERE a.id = :id
        ');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Récupère un article par slug
     */
    public function getBySlug($slug) {
        $stmt = $this->db->prepare('
            SELECT a.*, c.name as category_name, u.username as author_name
            FROM articles a
            LEFT JOIN categories c ON a.category_id = c.id
            LEFT JOIN users u ON a.author_id = u.id
            WHERE a.slug = :slug AND a.status = \'published\'
        ');
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }

    /**
     * Récupère les articles publiés avec pagination
     */
    public function getPublished($limit = 10, $offset = 0) {
        $stmt = $this->db->prepare('
            SELECT a.id, a.title, a.slug, a.excerpt, a.published_at, 
                   c.name as category_name, c.slug as category_slug, u.username as author_name
            FROM articles a
            LEFT JOIN categories c ON a.category_id = c.id
            LEFT JOIN users u ON a.author_id = u.id
            WHERE a.status = \'published\'
            ORDER BY a.published_at DESC
            LIMIT :limit OFFSET :offset
        ');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupère les brouillons d'un auteur
     */
    public function getDraftsBy($authorId) {
        $stmt = $this->db->prepare('
            SELECT id, title, slug, status, created_at
            FROM articles
            WHERE author_id = :author_id AND status = \'draft\'
            ORDER BY created_at DESC
        ');
        $stmt->execute([':author_id' => $authorId]);
        return $stmt->fetchAll();
    }

    /**
     * Met à jour un article
     */
    public function update($id, $data) {
        $allowed = ['title', 'content', 'excerpt', 'category_id', 'status', 'published_at'];
        $updates = ['updated_at = NOW()'];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowed)) {
                if ($key === 'title') {
                    $this->updateSlug($id, $value);
                }
                $updates[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }

        if (count($updates) === 1) return false;

        $query = 'UPDATE articles SET ' . implode(', ', $updates) . ' WHERE id = :id';
        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Récupère tous les articles (pour le backoffice)
     */
    public function getAll() {
        $stmt = $this->db->prepare('
            SELECT a.id, a.title, a.slug, a.status, a.created_at, a.published_at,
                   c.name as category_name, u.username as author_name
            FROM articles a
            LEFT JOIN categories c ON a.category_id = c.id
            LEFT JOIN users u ON a.author_id = u.id
            ORDER BY a.created_at DESC
        ');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupère les articles référencés par cet article
     */
    public function getRelatedArticles($articleId) {
        $stmt = $this->db->prepare('
            SELECT a.id, a.title, a.slug
            FROM articles a
            JOIN related_articles ra ON a.id = ra.related_id
            WHERE ra.article_id = :article_id AND a.status = \'published\'
        ');
        $stmt->execute([':article_id' => $articleId]);
        return $stmt->fetchAll();
    }

    /**
     * Synchronise les articles référencés
     */
    public function syncRelatedArticles($articleId, $relatedIds) {
        // Supprimer toutes les liaisons existantes pour cet article
        $stmt = $this->db->prepare('DELETE FROM related_articles WHERE article_id = :article_id');
        $stmt->execute([':article_id' => $articleId]);
        
        // Insérer les nouvelles liaisons
        if (!empty($relatedIds)) {
            $stmt = $this->db->prepare('INSERT INTO related_articles (article_id, related_id) VALUES (:article_id, :related_id) ON CONFLICT DO NOTHING');
            foreach ($relatedIds as $rId) {
                if ($rId != $articleId) { // Empêche un article d'être référencé avec lui-même
                    $stmt->execute([
                        ':article_id' => $articleId,
                        ':related_id' => $rId
                    ]);
                }
            }
        }
    }

    /**
     * Récupère les images associées à un article
     */
    public function getImages($articleId) {

        $stmt = $this->db->prepare('
            SELECT id, url, alt FROM images WHERE article_id = :article_id ORDER BY id ASC
        ');
        $stmt->execute([':article_id' => $articleId]);
        return $stmt->fetchAll();
    }

    /**
     * Supprime un article
     */
    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM articles WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }


    /**
     * Récupère le nombre total d'articles publiés
     */
    public function countPublished() {
        $stmt = $this->db->prepare('SELECT COUNT(*) as total FROM articles WHERE status = \'published\'');
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Crée un slug unique à partir d'une chaîne
     */
    private function slugify($str) {
        $str = mb_strtolower(trim($str), 'UTF-8');
        $str = preg_replace('/[^\p{L}\p{N}_\-\.\ ]/u', '', $str);
        $str = preg_replace('/[\s\-\.]+/', '-', $str);
        $str = trim($str, '-');
        
        // Vérifier si le slug existe déjà et ajouter un suffixe si nécessaire
        $baseSlug = $str;
        $counter = 1;
        $uniqueSlug = $baseSlug;
        
        while ($this->slugExists($uniqueSlug)) {
            $uniqueSlug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $uniqueSlug;
    }

    /**
     * Vérifie si un slug existe déjà
     */
    private function slugExists($slug, $excludeId = null) {
        $sql = 'SELECT COUNT(*) as count FROM articles WHERE slug = :slug';
        $params = [':slug' => $slug];
        
        if ($excludeId !== null) {
            $sql .= ' AND id != :id';
            $params[':id'] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    /**
     * Met à jour le slug d'un article avec vérification d'unicité
     */
    private function updateSlug($id, $title) {
        $baseSlug = $this->slugify($title);
        
        // Si le slug généré existe déjà pour un autre article, ajouter un suffixe
        $counter = 1;
        $slug = $baseSlug;
        while ($this->slugExists($slug, $id)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $stmt = $this->db->prepare('UPDATE articles SET slug = :slug WHERE id = :id');
        $stmt->execute([':slug' => $slug, ':id' => $id]);
    }
}

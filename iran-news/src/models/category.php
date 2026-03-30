<?php
/**
 * Modèle Category - Gestion des catégories
 */

class Category {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Crée une nouvelle catégorie
     */
    public function create($name, $slug = null) {
        if (!$slug) {
            $slug = $this->slugify($name);
        }

        $stmt = $this->db->prepare('
            INSERT INTO categories (name, slug, created_at)
            VALUES (:name, :slug, NOW())
            RETURNING id, name, slug
        ');

        return $stmt->execute([
            ':name' => $name,
            ':slug' => $slug
        ]) ? $stmt->fetch() : false;
    }

    /**
     * Récupère une catégorie par ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare('
            SELECT id, name, slug, created_at FROM categories WHERE id = :id
        ');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Récupère une catégorie par slug
     */
    public function getBySlug($slug) {
        $stmt = $this->db->prepare('
            SELECT id, name, slug, created_at FROM categories WHERE slug = :slug
        ');
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }

    /**
     * Récupère toutes les catégories
     */
    public function getAll() {
        $stmt = $this->db->prepare('
            SELECT id, name, slug, created_at FROM categories ORDER BY name ASC
        ');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupère les articles d'une catégorie
     */
    public function getArticles($categoryId, $limit = 10, $offset = 0) {
        $stmt = $this->db->prepare('
            SELECT a.id, a.title, a.slug, a.excerpt, a.created_at
            FROM articles a
            WHERE a.category_id = :category_id AND a.status = \'published\'
            ORDER BY a.published_at DESC
            LIMIT :limit OFFSET :offset
        ');
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Met à jour une catégorie
     */
    public function update($id, $name, $slug = null) {
        if (!$slug) {
            $slug = $this->slugify($name);
        }

        $stmt = $this->db->prepare('
            UPDATE categories SET name = :name, slug = :slug WHERE id = :id
        ');

        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':slug' => $slug
        ]);
    }

    /**
     * Supprime une catégorie
     */
    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM categories WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Crée un slug à partir d'une chaîne
     */
    private function slugify($str) {
        $str = mb_strtolower(trim($str), 'UTF-8');
        $str = preg_replace('/[^\p{L}\p{N}_\-\.\ ]/u', '', $str);
        $str = preg_replace('/[\s\-\.]+/', '-', $str);
        $str = trim($str, '-');
        return $str;
    }
}

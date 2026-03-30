<?php
/**
 * Modèle Tag - Gestion des tags
 */

class Tag {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Crée un nouveau tag
     */
    public function create($name, $slug = null) {
        if (!$slug) {
            $slug = $this->slugify($name);
        }

        $stmt = $this->db->prepare('
            INSERT INTO tags (name, slug)
            VALUES (:name, :slug)
            RETURNING id, name, slug
        ');

        return $stmt->execute([
            ':name' => $name,
            ':slug' => $slug
        ]) ? $stmt->fetch() : false;
    }

    /**
     * Récupère un tag par ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare('SELECT id, name, slug FROM tags WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Récupère tous les tags
     */
    public function getAll() {
        $stmt = $this->db->prepare('SELECT id, name, slug FROM tags ORDER BY name ASC');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupère les tags d'un article
     */
    public function getByArticle($articleId) {
        $stmt = $this->db->prepare('
            SELECT t.id, t.name, t.slug
            FROM tags t
            INNER JOIN article_tags at ON t.id = at.tag_id
            WHERE at.article_id = :article_id
        ');
        $stmt->execute([':article_id' => $articleId]);
        return $stmt->fetchAll();
    }

    /**
     * Ajoute un tag à un article
     */
    public function addToArticle($articleId, $tagId) {
        $stmt = $this->db->prepare('
            INSERT INTO article_tags (article_id, tag_id)
            VALUES (:article_id, :tag_id)
            ON CONFLICT DO NOTHING
        ');

        return $stmt->execute([
            ':article_id' => $articleId,
            ':tag_id' => $tagId
        ]);
    }

    /**
     * Supprime un tag d'un article
     */
    public function removeFromArticle($articleId, $tagId) {
        $stmt = $this->db->prepare('
            DELETE FROM article_tags
            WHERE article_id = :article_id AND tag_id = :tag_id
        ');

        return $stmt->execute([
            ':article_id' => $articleId,
            ':tag_id' => $tagId
        ]);
    }

    /**
     * Supprime un tag
     */
    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM tags WHERE id = :id');
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

<?php
/**
 * Classe Database - Gestion de la connexion PDO
 */

class Database {
    private static $instance = null;
    private $connection = null;

    /**
     * Pattern Singleton pour la connexion
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Connexion à la base de données
     */
    private function __construct() {
        try {
            require_once __DIR__ . '/../../config/database.php';
            
            $this->connection = new PDO(
                DB_DSN,
                DB_USER,
                DB_PASSWORD,
                PDO_OPTIONS
            );
        } catch (PDOException $e) {
            die('Erreur de connexion à la base de données: ' . $e->getMessage());
        }
    }

    /**
     * Récupère la connexion PDO
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Prépare et exécute une requête
     */
    public function prepare($query) {
        return $this->connection->prepare($query);
    }

    /**
     * Exécute une requête et retourne tous les résultats
     */
    public function fetchAll($query, $params = []) {
        $stmt = $this->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Exécute une requête et retourne un résultat
     */
    public function fetch($query, $params = []) {
        $stmt = $this->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * Exécute une requête (INSERT, UPDATE, DELETE)
     */
    public function execute($query, $params = []) {
        $stmt = $this->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Récupère l'ID du dernier enregistrement inséré
     */
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }

    /**
     * Empêcher le clonage
     */
    private function __clone() {}

    /**
     * Empêcher la désérialisation
     */
    public function __wakeup() {}
}

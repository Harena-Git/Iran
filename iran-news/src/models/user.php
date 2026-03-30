<?php
/**
 * Modèle User - Gestion des utilisateurs
 */

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Crée un nouvel utilisateur
     */
    public function create($username, $email, $password, $role = 'editor') {
        $stmt = $this->db->prepare('
            INSERT INTO users (username, email, password, role, created_at)
            VALUES (:username, :email, :password, :role, NOW())
            RETURNING id, username, email, role
        ');

        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_BCRYPT),
            ':role' => $role
        ]) ? $stmt->fetch() : false;
    }

    /**
     * Récupère un utilisateur par ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare('SELECT id, username, email, role, created_at FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Récupère un utilisateur par email
     */
    public function getByEmail($email) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    /**
     * Récupère un utilisateur par username
     */
    public function getByUsername($username) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute([':username' => $username]);
        return $stmt->fetch();
    }

    /**
     * Authentifie un utilisateur
     */
    public function authenticate($email, $password) {
        $user = $this->getByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    /**
     * Récupère tous les utilisateurs
     */
    public function getAll() {
        $stmt = $this->db->prepare('
            SELECT id, username, email, role, created_at 
            FROM users 
            ORDER BY created_at DESC
        ');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Met à jour un utilisateur
     */
    public function update($id, $data) {
        $allowed = ['username', 'email', 'role'];
        $updates = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowed)) {
                $updates[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }

        if (empty($updates)) return false;

        $query = 'UPDATE users SET ' . implode(', ', $updates) . ', updated_at = NOW() WHERE id = :id';
        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Supprime un utilisateur
     */
    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}

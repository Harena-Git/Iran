<?php
/**
 * Classe Session - Gestion des sessions utilisateur
 */

class Session {
    /**
     * Démarre la session si elle n'est pas démarrée
     */
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Définit une valeur en session
     */
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Récupère une valeur de session
     */
    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Supprime une valeur de session
     */
    public static function remove($key) {
        self::start();
        unset($_SESSION[$key]);
    }

    /**
     * Vérifie si une clé existe en session
     */
    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }

    /**
     * Détruit la session complète
     */
    public static function destroy() {
        self::start();
        session_destroy();
        $_SESSION = [];
    }

    /**
     * Vérifie si l'utilisateur est connecté
     */
    public static function isLoggedIn() {
        return self::has('user_id');
    }

    /**
     * Récupère l'utilisateur connecté
     */
    public static function getUser() {
        if (self::isLoggedIn()) {
            return [
                'id' => self::get('user_id'),
                'username' => self::get('username'),
                'email' => self::get('email')
            ];
        }
        return null;
    }

    /**
     * Connecte un utilisateur
     */
    public static function login($userId, $username, $email) {
        self::start();
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['login_time'] = time();
    }

    /**
     * Déconnecte l'utilisateur
     */
    public static function logout() {
        self::destroy();
    }

    /**
     * Crée un token CSRF
     */
    public static function generateCSRFToken() {
        if (!self::has('csrf_token')) {
            self::set('csrf_token', bin2hex(random_bytes(32)));
        }
        return self::get('csrf_token');
    }

    /**
     * Vérifie un token CSRF
     */
    public static function verifyCSRFToken($token) {
        return hash_equals(self::get('csrf_token', ''), $token);
    }
}

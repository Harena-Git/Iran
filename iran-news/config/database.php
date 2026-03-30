<?php
/**
 * Configuration Base de Données PostgreSQL
 */

// Charger les variables d'environnement
function getEnvCustom($key, $default = null) {
    if (file_exists(__DIR__ . '/../.env')) {
        $env = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($env as $line) {
            if (strpos($line, '=') !== false && $line[0] !== '#') {
                list($envKey, $envValue) = explode('=', $line, 2);
                if (trim($envKey) === $key) {
                    return trim($envValue);
                }
            }
        }
    }
    return $_ENV[$key] ?? $default;
}

// Configuration
define('DB_HOST', getEnvCustom('DB_HOST', 'localhost'));
define('DB_PORT', getEnvCustom('DB_PORT', '5432'));
define('DB_NAME', getEnvCustom('DB_NAME', 'iran_news'));
define('DB_USER', getEnvCustom('DB_USER', 'postgres'));
define('DB_PASSWORD', getEnvCustom('DB_PASSWORD', 'password'));

// DSN PostgreSQL
define('DB_DSN', 'pgsql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME);

// Options PDO
define('PDO_OPTIONS', [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
]);

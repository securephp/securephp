<?php
namespace SecurePHP\Helpers;

class RequestHelper
{
    /**
     * Récupère une valeur POST nettoyée
     */
    public static function post(string $key, $default = null) {
        return isset($_POST[$key]) ? htmlspecialchars($_POST[$key], ENT_QUOTES, 'UTF-8') : $default;
    }

    /**
     * Récupère une valeur GET nettoyée
     */
    public static function get(string $key, $default = null) {
        return isset($_GET[$key]) ? htmlspecialchars($_GET[$key], ENT_QUOTES, 'UTF-8') : $default;
    }

    /**
     * Vérifie la méthode HTTP
     */
    public static function isPost(): bool {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public static function isGet(): bool {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Retourne l’adresse IP du client
     */
    public static function clientIP(): string {
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
}
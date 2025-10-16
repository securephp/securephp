<?php
// Validator.php
namespace SecurePHP;

require_once 'Encryption.php';

use SecurePHP\Encryption;

class Validator
{
    /**
     * Vérifie si une chaîne n'est pas vide
     */
    public static function notEmpty(string $value): bool
    {
        return !empty(trim($value));
    }

    /**
     * Vérifie la longueur d'une chaîne
     */
    public static function length(string $value, int $min = 0, int $max = PHP_INT_MAX): bool
    {
        $len = mb_strlen(trim($value));
        return $len >= $min && $len <= $max;
    }

    /**
     * Vérifie si c'est un email valide
     */
    public static function isEmail(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Vérifie si c'est un mot de passe sécurisé
     * Minimum 8 caractères, au moins 1 lettre, 1 chiffre
     */
    public static function isPassword(string $value): bool
    {
        return preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $value) === 1;
    }

    /**
     * Vérifie si c'est un URL valide
     */
    public static function isURL(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Vérifie si c'est un numéro de téléphone valide (ex: +243...)
     */
    public static function isPhone(string $value): bool
    {
        return preg_match('/^\+?[0-9]{7,15}$/', $value) === 1;
    }

    /**
     * Vérifie si c'est un nombre
     */
    public static function isNumber($value): bool
    {
        return is_numeric($value);
    }

    /**
     * Nettoie une chaîne pour éviter les XSS et caractères spéciaux
     */
    public static function sanitize(string $value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Hache automatiquement un mot de passe
     */
    public static function hashPassword(string $password): string
    {
        return Encryption::hashPassword($password);
    }

    /**
     * Vérifie un mot de passe contre son hash
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return Encryption::verifyPassword($password, $hash);
    }

    /**
     * Chiffre une donnée avec une clé
     */
    public static function encrypt(string $data, string $key): string
    {
        return Encryption::encrypt($data, $key);
    }

    /**
     * Déchiffre une donnée avec une clé
     */
    public static function decrypt(string $data, string $key): string|false
    {
        return Encryption::decrypt($data, $key);
    }

    /**
     * Génère un token sécurisé pour sessions ou API
     */
    public static function generateToken(int $length = 32): string
    {
        return Encryption::generateToken($length);
    }
}
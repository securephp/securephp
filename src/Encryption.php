<?php
// Encryption.php
namespace SecurePHP;

class Encryption
{
    private static string $method = 'AES-256-CBC';

    /**
     * Génère une clé aléatoire sécurisée
     */
    public static function generateKey(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Chiffre une donnée avec AES-256-CBC
     */
    public static function encrypt(string $data, string $key): string
    {
        $iv = random_bytes(openssl_cipher_iv_length(self::$method));
        $encrypted = openssl_encrypt($data, self::$method, $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    /**
     * Déchiffre une donnée avec AES-256-CBC
     */
    public static function decrypt(string $data, string $key): string|false
    {
        $data = base64_decode($data);
        $ivLength = openssl_cipher_iv_length(self::$method);
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);
        return openssl_decrypt($encrypted, self::$method, $key, 0, $iv);
    }

    /**
     * Hache un mot de passe (Argon2id ou bcrypt selon disponibilité)
     */
    public static function hashPassword(string $password): string
    {
        if (defined('PASSWORD_ARGON2ID')) {
            return password_hash($password, PASSWORD_ARGON2ID);
        }
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Vérifie un mot de passe contre son hash
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Génère un token aléatoire sécurisé pour sessions ou API
     */
    public static function generateToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }
}
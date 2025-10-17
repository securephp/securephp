<?php
namespace SecurePHP;

/**
 * Classe de gestion des tokens CSRF (Cross-Site Request Forgery)
 * 
 * Gère la génération, la vérification et la durée de vie d’un token CSRF sécurisé.
 * 
 * @package SecurePHP
 */
class CSRF
{
    private const TOKEN_KEY = '_csrf_token';
    private const TOKEN_EXP_KEY = '_csrf_token_exp';
    private const DEFAULT_TTL = 900; // 15 minutes

    /**
     * Génère un nouveau token CSRF et le stocke en session
     */
    public static function generateToken(int $ttl = self::DEFAULT_TTL): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Si un token valide existe déjà, on le réutilise pour éviter les conflits
        if (isset($_SESSION[self::TOKEN_KEY], $_SESSION[self::TOKEN_EXP_KEY])
            && time() < $_SESSION[self::TOKEN_EXP_KEY]) {
            return $_SESSION[self::TOKEN_KEY];
        }

        // Sinon, on en génère un nouveau
        $token = bin2hex(random_bytes(32));
        $_SESSION[self::TOKEN_KEY] = $token;
        $_SESSION[self::TOKEN_EXP_KEY] = time() + $ttl;

        return $token;
    }

    /**
     * Vérifie si le token soumis est valide
     */
    public static function verifyToken(?string $token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($token) || !isset($_SESSION[self::TOKEN_KEY])) {
            return false;
        }

        $validToken = $_SESSION[self::TOKEN_KEY];
        $expiry = $_SESSION[self::TOKEN_EXP_KEY] ?? 0;

        // Validation
        $isValid = hash_equals($validToken, $token) && time() <= $expiry;

        // ⚠️ On ne détruit plus la session ici, pour éviter le "Token invalide"
        // unset($_SESSION[self::TOKEN_KEY], $_SESSION[self::TOKEN_EXP_KEY]);

        return $isValid;
    }

    /**
     * Retourne le champ caché HTML pour formulaires
     */
    public static function inputField(): string
    {
        $token = self::generateToken();
        return '<input type="hidden" name="_csrf_token" value="' 
             . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') 
             . '">';
    }
}
<?php
namespace SecurePHP;

class CSRF
{
    private const TOKEN_KEY = '_csrf_token';
    private const TOKEN_EXP_KEY = '_csrf_token_exp';
    private const DEFAULT_TTL = 900; // 15 minutes

    public static function generateToken(int $ttl = self::DEFAULT_TTL): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = bin2hex(random_bytes(32));
        $_SESSION[self::TOKEN_KEY] = $token;
        $_SESSION[self::TOKEN_EXP_KEY] = time() + $ttl;
        return $token;
    }

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

        $ok = hash_equals($validToken, $token) && ($expiry === 0 || time() <= $expiry);

        // Unset to prevent reuse (single use token)
        unset($_SESSION[self::TOKEN_KEY], $_SESSION[self::TOKEN_EXP_KEY]);

        return $ok;
    }

    /**
     * Retourne le champ hidden HTML à insérer dans les formulaires
     */
    public static function inputField(): string
    {
        $token = self::generateToken();
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
}
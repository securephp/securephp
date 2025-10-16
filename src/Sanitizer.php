<?php
namespace SecurePHP;

class Sanitizer
{
    /**
     * Nettoie récursivement les superglobales _GET, _POST, _COOKIE
     */
    public static function cleanGlobals(): void
    {
        $globals = ['_GET', '_POST', '_COOKIE'];

        foreach ($globals as $g) {
            if (!isset($GLOBALS[$g]) || !is_array($GLOBALS[$g])) {
                continue;
            }
            $GLOBALS[$g] = self::cleanRecursive($GLOBALS[$g]);
        }
    }

    /**
     * Nettoyage récursif des valeurs (retourne tableau nettoyé)
     */
    private static function cleanRecursive(array $data): array
    {
        $clean = [];
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $clean[$k] = self::cleanRecursive($v);
            } elseif (is_string($v)) {
                // Trim + htmlspecialchars (UTF-8)
                $clean[$k] = htmlspecialchars(trim($v), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            } else {
                // laisser int/float/bool tels quels
                $clean[$k] = $v;
            }
        }
        return $clean;
    }
}
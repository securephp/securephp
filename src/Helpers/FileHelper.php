<?php
namespace SecurePHP\Helpers;

class FileHelper
{
    /**
     * Vérifie si un fichier est sûr à téléverser
     */
    public static function isSafeFile(string $filename, array $allowedExtensions = ['jpg','png','pdf','txt']): bool {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($ext, $allowedExtensions, true);
    }

    /**
     * Nettoie un nom de fichier pour éviter les attaques par injection
     */
    public static function sanitizeFileName(string $filename): string {
        return preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $filename);
    }

    /**
     * Vérifie la taille d’un fichier
     */
    public static function isSizeAllowed(string $filePath, int $maxSize = 2097152): bool { // 2 Mo
        return file_exists($filePath) && filesize($filePath) <= $maxSize;
    }
}
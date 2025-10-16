<?php
namespace SecurePHP;

class Session
{
    public static function start(array $options = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            // valeurs par défaut sécurisées
            $defaults = [
                'lifetime' => 3600,
                'path' => '/',
                'domain' => '',
                'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
                'httponly' => true,
                'samesite' => 'Lax'
            ];
            $opts = array_merge($defaults, $options);
            session_set_cookie_params([
                'lifetime' => $opts['lifetime'],
                'path' => $opts['path'],
                'domain' => $opts['domain'],
                'secure' => $opts['secure'],
                'httponly' => $opts['httponly'],
                'samesite' => $opts['samesite']
            ]);
            session_start();
        }
    }

    public static function destroy(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params['path'], $params['domain'],
                    $params['secure'] ?? false, $params['httponly'] ?? true
                );
            }
            session_destroy();
        }
    }

    public static function harden(): void
    {
        self::start();

        // Régénérer l'ID périodiquement
        if (!isset($_SESSION['created'])) {
            $_SESSION['created'] = time();
        } elseif (time() - $_SESSION['created'] > 300) { // regen toutes les 5 min
            session_regenerate_id(true);
            $_SESSION['created'] = time();
        }

        // Bind IP + UA (simple check)
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

        if (!isset($_SESSION['ip_address'])) {
            $_SESSION['ip_address'] = $ip;
        } elseif ($_SESSION['ip_address'] !== $ip) {
            self::destroy();
            exit('Session hijacking attempt detected');
        }

        if (!isset($_SESSION['user_agent'])) {
            $_SESSION['user_agent'] = $ua;
        } elseif ($_SESSION['user_agent'] !== $ua) {
            self::destroy();
            exit('User-Agent mismatch detected');
        }
    }
}
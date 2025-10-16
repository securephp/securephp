<?php
namespace SecurePHP;

class Security
{
    private Logger $logger;

    public function __construct(?Logger $logger = null)
    {
        $this->logger = $logger ?? new Logger();
        // démarrer la session de base (mais ne harden pas encore)
        Session::start();
    }

    /**
     * Protection complète à appeler en tête de page
     */
    public function autoProtect(): void
    {
        $this->setSecureHeaders();
        $this->sanitizeInputs();
        $this->csrfProtect();
        $this->secureSession();
    }

    public function sanitizeInputs(): void
    {
        Sanitizer::cleanGlobals();
        $this->logger->info('Inputs sanitized.');
    }

    public function csrfProtect(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['_csrf_token'] ?? $_POST['csrf_token'] ?? null;
            if (!CSRF::verifyToken($token)) {
                $this->logger->alert('CSRF token invalid — potential attack blocked.');
                http_response_code(400);
                die('CSRF token invalid');
            }
        }
    }

    public function secureSession(): void
    {
        Session::harden();
        $this->logger->info('Session hardened.');
    }

    public function setSecureHeaders(): void
    {
        // appeler avant tout output
        header('X-Frame-Options: SAMEORIGIN');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: no-referrer');
        header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self';");
    }
}
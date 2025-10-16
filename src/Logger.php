<?php
namespace SecurePHP;

class Logger
{
    private string $logFile;

    public function __construct(?string $file = null)
    {
        $this->logFile = $file ?? __DIR__ . '/../logs/security.log';
        $this->ensureLogDirectory();
    }

    private function ensureLogDirectory(): void
    {
        $dir = dirname($this->logFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    private function write(string $level, string $message): void
    {
        $date = date('Y-m-d H:i:s');
        $entry = "[$date] [$level] $message" . PHP_EOL;
        // Écriture atomique avec verrou
        file_put_contents($this->logFile, $entry, FILE_APPEND | LOCK_EX);
    }

    public function log(string $message): void
    {
        $this->write('LOG', $message);
    }

    public function info(string $message): void
    {
        $this->write('INFO', $message);
    }

    public function error(string $message): void
    {
        $this->write('ERROR', $message);
    }

    public function alert(string $message): void
    {
        $this->write('ALERT', $message);
    }

    public function clear(): void
    {
        if (file_exists($this->logFile)) {
            unlink($this->logFile);
        }
    }

    public function read(): string
    {
        return file_exists($this->logFile) ? file_get_contents($this->logFile) : '';
    }
}
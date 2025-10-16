<?php
require_once __DIR__ . '/../vendor/autoload.php';

use SecurePHP\Security;
use SecurePHP\CSRF;

echo "=== TEST SecurePHP ===\n";

$secure = new Security();
$secure->autoProtect();

$token = CSRF::generateToken();
echo "CSRF Token généré : $token\n";

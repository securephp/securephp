<?php
require_once __DIR__ . '/../vendor/autoload.php';

use SecurePHP\Sanitizer;

echo "=== TEST Sanitizer ===\n";

$_POST = ['name' => '<script>alert(1)</script>'];
Sanitizer::cleanGlobals();

echo "Résultat nettoyé : " . $_POST['name'] . "\n";

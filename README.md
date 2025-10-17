
# SecurePHP

SecurePHP est une bibliothèque PHP légère pour sécuriser vos applications web.
Elle fournit des fonctionnalités de protection CSRF, sécurisation de session, et inclut des helpers pour gérer les fichiers et les requêtes

### ⚡ Installation

1. Copier le dossier `src/` et `test/` dans votre projet.

2. Inclure les fichiers nécessaires via `require_once` (ou autoload personnalisé) :

```bash
require_once __DIR__ . '/src/Session.php';
require_once __DIR__ . '/src/Logger.php';
require_once __DIR__ . '/src/Sanitizer.php';
require_once __DIR__ . '/src/CSRF.php';
require_once __DIR__ . '/src/Security.php';
require_once __DIR__ . '/src/Helpers/FileHelper.php';
require_once __DIR__ . '/src/Helpers/RequestHelper.php';
```

3. Initialiser la sécurité au début de chaque page :
```bash
use SecurePHP\Security;
use SecurePHP\Session;

Session::start();        // Démarre la session
$security = new Security();
$security->autoProtect(); // Applique la sanitization, CSRF, session hardening et headers
```

<br>

### 🛡️ Utilisation principale

1. Protection CSRF dans un formulaire
```bash
<form method="post" action="">
    <?= \SecurePHP\CSRF::inputField() ?>
    <input type="text" name="username" required>
    <input type="email" name="email" required>
    <input type="password" name="password" required>
    <button type="submit">S'inscrire</button>
</form>
```
Le token CSRF est automatiquement vérifié grâce à Security::autoProtect().

2. Helpers

FileHelper
```bash
use SecurePHP\Helpers\FileHelper;

if (FileHelper::isSafeFile($_FILES['avatar']['name'])) {
    $safeName = FileHelper::sanitizeFileName($_FILES['avatar']['name']);
}
```

RequestHelper
```bash
use SecurePHP\Helpers\RequestHelper;

$username = RequestHelper::post('username');
$email    = RequestHelper::post('email');
$ip       = RequestHelper::clientIP();
```

<br>

### 🧪 Tests

1. Test du Sanitizer

```bash
php test/SanitizerTest.php
```

Exemple de sortie :
```diff
=== TEST Sanitizer ===
Résultat nettoyé : &lt;script&gt;alert(1)&lt;/script&gt;
```

2. Test de Security + CSRF
```bash
php test/SecurityTest.php
```

Exemple de sortie :
```diff
=== TEST SecurePHP ===
CSRF Token généré : 3b9f... (token aléatoire)
```

<br>

### 
📝 Notes

<ul>
    <li>Les logs de sécurité sont stockés dans logs/security.log.</li>
    <li>Les sessions sont renforcées (harden()) : régénération ID, IP/User-Agent bind.</li>
    <li>Les entrées utilisateurs sont automatiquement nettoyées contre XSS.</li>
    <li>CSRF token à usage unique avec expiration configurable (15 minutes par défaut).</li>
    <li>Helpers pour fichiers et requêtes sécurisent vos uploads et données POST/GET.</li>
</ul>
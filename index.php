<?php
// index.php - The Front Controller

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
    // Define the global Base URL for all links and assets
    define('BASE_URL', '/Nritya_House');
}

function loadEnv($path)
{
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . "=" . trim($value));
        $_SERVER[trim($name)] = trim($value);
    }
}
// ... (Environment loader code above)
loadEnv(__DIR__ . '/.env');

// --- THE AUTOLOADER ---
// This automatically finds and requires your classes so you don't have to.
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
// ----------------------

// Load Core Components (We no longer strictly need these requires if they are correctly namespaced, but keep them for now)
require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Core/Security.php';
require_once __DIR__ . '/app/Core/Router.php';

// ... (Rest of index.php below)
// Global CSRF Protection Check for all POST requests
\App\Core\Security::verifyCsrf();

// Boot the Router
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'home';
$router = new \App\Core\Router();
$router->dispatch($url);

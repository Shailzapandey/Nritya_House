<?php

namespace App\Core;

class Security
{
    // Generate a secure, random token and store it in the session
    public static function generateCsrfToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // Output the hidden HTML input for your forms
    public static function csrfField()
    {
        $token = self::generateCsrfToken();
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

    // Validate the token on POST requests
    public static function verifyCsrf()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                // If the token is missing or doesn't match, kill the request immediately.
                http_response_code(403);
                die("Security Error: Invalid or missing CSRF token. Request blocked.");
            }
        }
    }
}

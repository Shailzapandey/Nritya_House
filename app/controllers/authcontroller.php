<?php

namespace App\Controllers;

use App\Models\User;

class AuthController
{

    // Display the Login View
    public function login()
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: dashboard"); // Route to dashboard if already logged in
            exit;
        }
        require_once __DIR__ . '/../../views/auth/login.php';
    }

    // Display the Register View
    public function register()
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: dashboard");
            exit;
        }
        require_once __DIR__ . '/../../views/auth/register.php';
    }

    // Process Login POST request
    public function processLogin()
    {
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            // CRITICAL FIX: Prevent Session Fixation attacks
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];

            header("Location: ../dashboard"); // Use relative path to hit the router
            exit;
        } else {
            // Redirect back with an error
            header("Location: ../auth/login?error=invalid_credentials");
            exit;
        }
    }

    // Process Register POST request
    public function processRegister()
    {
        $name = trim($_POST['full_name'] ?? '');
        // Apply htmlspecialchars ON OUTPUT, not on input. Storing raw data.
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (strlen($password) < 8) {
            header("Location: ../auth/register?error=password_too_short");
            exit;
        }

        $userModel = new User();

        if ($userModel->findByEmail($email)) {
            header("Location: ../auth/register?error=email_exists");
            exit;
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        if ($userModel->create($name, $email, $passwordHash)) {
            header("Location: ../auth/login?msg=registered");
            exit;
        } else {
            die("System Error: Registration failed.");
        }
    }

    // Handle Logout
    public function logout()
    {
        session_unset();
        session_destroy();
        header("Location: ../home");
        exit;
    }
}

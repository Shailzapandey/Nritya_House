<?php
session_start();
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- REGISTRATION LOGIC ---
    if (isset($_POST['action']) && $_POST['action'] == 'register') {
        $name = htmlspecialchars(trim($_POST['full_name']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        try {
            $check_stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
            $check_stmt->execute([$email]);

            if ($check_stmt->rowCount() > 0) {
                die("Email already exists.");
            }

            $insert_sql = "INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($insert_sql);

            if ($stmt->execute([$name, $email, $password_hash])) {
                // Change from yesterday: Automatically redirect to login page after success
                header("Location: ../login.php");
                exit;
            }
        } catch (PDOException $e) {
            die("Registration failed: " . $e->getMessage());
        }
    }

    // --- NEW: LOGIN LOGIC ---
    if (isset($_POST['action']) && $_POST['action'] == 'login') {
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        try {
            // 1. Find the user by email
            $stmt = $pdo->prepare("SELECT user_id, full_name, password_hash, role FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // 2. Verify if user exists AND the password matches the hash
            if ($user && password_verify($password, $user['password_hash'])) {
                // 3. SUCCESS: Create the session variables (The "VIP Pass")
                // 3. SUCCESS: Create the session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];

                // 4. Redirect to the protected dashboard
                header("Location: ../dashboard.php");
                exit;
            } else {
                // ARCHITECT RULE: Never tell them IF the email was wrong OR the password was wrong. 
                // Just say "Invalid credentials" to prevent email harvesting.
                die("Invalid credentials. Please try again.");
            }
        } catch (PDOException $e) {
            die("Login failed: " . $e->getMessage());
        }
    }
} else {
    header("Location: ../index.php");
    exit;
}

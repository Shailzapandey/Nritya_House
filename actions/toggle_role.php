<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';

// 1. Strict Security Guard
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target_user_id = $_POST['target_user_id'] ?? '';
    $current_role = $_POST['current_role'] ?? '';
    $acting_admin_id = $_SESSION['user_id'];

    // 2. The Self-Lockout Safeguard
    if ($target_user_id == $acting_admin_id && $current_role === 'admin') {
        header("Location: ../admin_users.php?error=self_demotion");
        exit;
    }

    // 3. Determine the new role
    $new_role = ($current_role === 'admin') ? 'student' : 'admin';

    // 4. Execute the update
    try {
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE user_id = ?");
        $stmt->execute([$new_role, $target_user_id]);

        header("Location: ../admin_users.php?msg=role_updated");
        exit;
    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
} else {
    header("Location: ../admin_users.php");
    exit;
}

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';

// 1. Guard Clauses
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$new_level = $_POST['experience_level'] ?? '';

// 2. Strict Validation: Do not trust the frontend dropdown
$allowed_levels = ['Beginner', 'Intermediate', 'Advanced'];
if (!in_array($new_level, $allowed_levels)) {
    die("Security Error: Invalid level selection.");
}

// 3. Execute the Update
try {
    $stmt = $pdo->prepare("UPDATE users SET experience_level = ? WHERE user_id = ?");
    $stmt->execute([$new_level, $user_id]);

    // 4. Route back with success message
    header("Location: ../profile.php?msg=updated");
    exit;
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

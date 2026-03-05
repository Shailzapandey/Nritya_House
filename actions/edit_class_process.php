<?php
// actions/edit_class_process.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';

// 1. STRICT ADMIN GUARD
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access. Admin privileges required.");
}

// 2. CATCH AND VALIDATE THE PAYLOAD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_id = $_POST['class_id'] ?? '';
    $title = trim($_POST['title'] ?? '');
    $instructor = trim($_POST['instructor'] ?? '');
    $difficulty_level = $_POST['difficulty_level'] ?? '';

    if (empty($class_id) || empty($title) || empty($instructor) || empty($difficulty_level)) {
        die("Error: All fields are required to update the class.");
    }

    try {
        // 3. EXECUTE THE SURGICAL UPDATE
        // Note: I am assuming your primary key is 'class_id'.
        $sql = "UPDATE classes SET title = ?, instructor = ?, difficulty_level = ? WHERE class_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $instructor, $difficulty_level, $class_id]);

        // 4. ROUTE BACK TO SAFETY
        header("Location: ../admin_classes.php?msg=updated");
        exit;
    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
} else {
    // Kick out users who try to access this URL directly without a POST request
    header("Location: ../admin_classes.php");
    exit;
}

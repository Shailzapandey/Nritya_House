<?php
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
    $original_last_updated = $_POST['original_last_updated'] ?? ''; // The OCC snapshot

    $title = trim($_POST['title'] ?? '');
    $instructor = trim($_POST['instructor'] ?? '');
    $difficulty_level = $_POST['difficulty_level'] ?? '';

    if (empty($class_id) || empty($original_last_updated) || empty($title) || empty($instructor) || empty($difficulty_level)) {
        die("Error: All fields and the concurrency token are required.");
    }

    try {
        // 3. OPTIMISTIC CONCURRENCY CONTROL: Update only if timestamp matches
        $sql = "UPDATE classes SET title = ?, instructor = ?, difficulty_level = ? 
                WHERE class_id = ? AND last_updated = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $instructor, $difficulty_level, $class_id, $original_last_updated]);

        // 4. Evaluate the result
        if ($stmt->rowCount() === 0) {
            // 0 rows updated means the timestamp shifted. Race condition intercepted.
            die("<div style='background: #fee2e2; color: #dc2626; padding: 20px; text-align: center; font-family: sans-serif; margin: 50px auto; max-width: 600px; border-radius: 8px; border: 1px solid #f87171;'>
                    <h2><i class='fa-solid fa-triangle-exclamation'></i> Concurrency Conflict Detected</h2>
                    <p>Another administrator has modified this course while you were editing it.</p>
                    <p>To prevent data loss, your changes have been blocked.</p>
                    <a href='../admin_classes.php' style='display: inline-block; margin-top: 15px; padding: 10px 20px; background: #dc2626; color: white; text-decoration: none; border-radius: 4px;'>Return to Dashboard</a>
                 </div>");
        }

        header("Location: ../admin_classes.php?msg=class_updated");
        exit;
    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
} else {
    header("Location: ../admin_classes.php");
    exit;
}

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';

// 1. STRICT ADMIN GUARD
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access. Admin privileges required.");
}

// 2. CATCH THE PAYLOAD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_id = $_POST['class_id'] ?? '';
    $lesson_title = trim($_POST['lesson_title'] ?? '');
    $video_url = trim($_POST['video_url'] ?? '');
    $order_index = $_POST['order_index'] ?? 1;

    if (empty($class_id) || empty($lesson_title) || empty($video_url)) {
        die("Error: All fields are required.");
    }

    // 3. THE MASTER URL SANITIZER (Catches both 'watch?v=' and 'youtu.be/')
    if (strpos($video_url, 'watch?v=') !== false) {
        $video_id = explode('watch?v=', $video_url)[1];
        $video_id = explode('&', $video_id)[0];
        $video_url = "https://www.youtube.com/embed/" . $video_id;
    } elseif (strpos($video_url, 'youtu.be/') !== false) {
        $video_id = explode('youtu.be/', $video_url)[1];
        $video_id = explode('?', $video_id)[0];
        $video_url = "https://www.youtube.com/embed/" . $video_id;
    }

    // 4. EXECUTE THE INSERTION
    try {
        $stmt = $pdo->prepare("INSERT INTO lessons (class_id, lesson_title, video_url, order_index) VALUES (?, ?, ?, ?)");
        $stmt->execute([$class_id, $lesson_title, $video_url, $order_index]);

        // Route back to the catalog with a success message
        header("Location: ../admin_classes.php?msg=lesson_added");
        exit;
    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
} else {
    header("Location: ../admin_classes.php");
    exit;
}

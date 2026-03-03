<?php
// actions/update_progress.php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['class_id'])) {

    $user_id = $_SESSION['user_id'];
    $class_id = intval($_POST['class_id']);

    try {
        // 1. Update the course progress
        $sql = "UPDATE enrollments 
                SET progress_percent = LEAST(progress_percent + 25, 100) 
                WHERE user_id = ? AND class_id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $class_id]);

        // 2. THE STREAK ENGINE: Log the daily activity
        // We use INSERT IGNORE so it won't crash if they complete multiple lessons in one day
        $log_sql = "INSERT IGNORE INTO user_activity_logs (user_id, activity_date) VALUES (?, CURDATE())";
        $log_stmt = $pdo->prepare($log_sql);
        $log_stmt->execute([$user_id]);

        // Send them back to the lesson page to see the updated percentage
        header("Location: ../lesson.php?id=" . $class_id);
        exit;
    } catch (PDOException $e) {
        die("Failed to update progress: " . $e->getMessage());
    }
} else {
    header("Location: ../dashboard.php");
    exit;
}

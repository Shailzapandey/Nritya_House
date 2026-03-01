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
        // We use an UPDATE query to add 25 to the current progress, 
        // but we use LEAST() to ensure it never goes above 100.
        $sql = "UPDATE enrollments 
                SET progress_percent = LEAST(progress_percent + 25, 100) 
                WHERE user_id = ? AND class_id = ?";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $class_id]);

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
?>
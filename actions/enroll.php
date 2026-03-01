<?php
// actions/enroll.php
session_start();
require_once '../config/database.php';

// 1. SECURITY: Kick out anyone who is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// 2. VALIDATION: Ensure the request came from the form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['class_id'])) {

    $user_id = $_SESSION['user_id'];
    $class_id = intval($_POST['class_id']);

    try {
        // 3. THE GUARD CHECK: Does this enrollment already exist?
        $check_stmt = $pdo->prepare("SELECT user_id FROM enrollments WHERE user_id = ? AND class_id = ?");
        $check_stmt->execute([$user_id, $class_id]);

        if ($check_stmt->rowCount() > 0) {
            // Threat averted: They are already enrolled. Send them to their dashboard quietly.
            header("Location: ../dashboard.php?msg=already_enrolled");
            exit;
        }

        // 4. THE INSERTION: Safe to enroll the student
        $insert_stmt = $pdo->prepare("INSERT INTO enrollments (user_id, class_id) VALUES (?, ?)");

        if ($insert_stmt->execute([$user_id, $class_id])) {
            // Success: Send them to their dashboard to start learning
            header("Location: ../dashboard.php?msg=enrolled");
            exit;
        }
    } catch (PDOException $e) {
        die("System Error: Failed to process enrollment. " . $e->getMessage());
    }
} else {
    // If they tried to access this file directly via URL
    header("Location: ../classes.php");
    exit;
}

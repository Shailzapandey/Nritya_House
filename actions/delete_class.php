<?php
// actions/delete_class.php
session_start();
require_once '../config/database.php';

// STRICT SECURITY: Only Admins can execute deletions
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized action. Admin privileges required.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['class_id'])) {
    
    $class_id = intval($_POST['class_id']);

    try {
        $sql = "DELETE FROM classes WHERE class_id = ?";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$class_id])) {
            header("Location: ../admin_classes.php?msg=deleted");
            exit;
        }

    } catch (PDOException $e) {
        die("Failed to delete class: " . $e->getMessage());
    }

} else {
    header("Location: ../admin_classes.php");
    exit;
}
?>
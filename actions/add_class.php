<?php
// actions/add_class.php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized action.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Sanitize standard text inputs
    $title = htmlspecialchars(trim($_POST['title']));
    $instructor = htmlspecialchars(trim($_POST['instructor']));
    $style = $_POST['style'];
    $difficulty = $_POST['difficulty_level'];
    $duration = intval($_POST['duration_min']);
    $video_url = filter_var(trim($_POST['video_url']), FILTER_SANITIZE_URL);

    // 2. THE FILE UPLOAD LOGIC
    $thumbnail_filename = 'default_thumb.jpg'; // Default fallback

    // Check if a file was actually uploaded without errors
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {

        $upload_dir = '../assets/images/';
        $file_extension = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));

        // Security: Only allow specific image types
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($file_extension, $allowed_extensions)) {
            die("Invalid file type. Only JPG, PNG, and WEBP are allowed.");
        }

        // Security: Create a unique filename using the current timestamp
        // If two admins upload "cover.jpg", we don't want them to overwrite each other.
        $new_filename = uniqid('thumb_') . '.' . $file_extension;
        $target_path = $upload_dir . $new_filename;

        // Move the file from PHP's temporary memory to our hard drive
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target_path)) {
            $thumbnail_filename = $new_filename; // Update the variable for the database
        } else {
            die("Server error: Failed to move uploaded file.");
        }
    }

    try {
        // 3. The SQL statement now includes thumbnail_url
        $sql = "INSERT INTO classes (title, instructor, style, difficulty_level, duration_min, video_url, thumbnail_url) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);

        // Execute with the $thumbnail_filename
        if ($stmt->execute([$title, $instructor, $style, $difficulty, $duration, $video_url, $thumbnail_filename])) {
            header("Location: ../admin_classes.php?msg=added");
            exit;
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}

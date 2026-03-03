<?php
// assessment.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

// Guard: Kick out users who aren't logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle the Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['level'])) {
    $selected_level = $_POST['level'];

    // Strict validation against injection
    $allowed_levels = ['Beginner', 'Intermediate', 'Advanced'];
    if (in_array($selected_level, $allowed_levels)) {
        try {
            // Check your primary key name here! I am using 'id'. If yours is 'user_id', change it.
            $update_stmt = $pdo->prepare("UPDATE users SET experience_level = ? WHERE user_id = ?");
            $update_stmt->execute([$selected_level, $user_id]);

            // Assessment complete. Release the choke-point and send to dashboard.
            header("Location: dashboard.php");
            exit;
        } catch (PDOException $e) {
            die("Database Error: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Level Assessment - Nritya House</title>
    <style>
        body {
            font-family: system-ui, sans-serif;
            background: #f4f4f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .assessment-card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .level-btn {
            display: block;
            width: 100%;
            padding: 15px;
            margin: 15px 0;
            border: 2px solid #ddd;
            border-radius: 8px;
            background: white;
            font-size: 1.1rem;
            cursor: pointer;
            transition: 0.2s;
            font-weight: bold;
        }

        .level-btn:hover {
            border-color: #ec4899;
            color: #ec4899;
            background: #fdf2f8;
        }
    </style>
</head>

<body>

    <div class="assessment-card">
        <h2 style="margin-top: 0; color: #333;">Welcome to Nritya House</h2>
        <p style="color: #666; margin-bottom: 30px;">To personalize your course catalog, please select your current dance experience level.</p>

        <form action="assessment.php" method="POST">
            <button type="submit" name="level" value="Beginner" class="level-btn">Beginner (0-1 Years)</button>
            <button type="submit" name="level" value="Intermediate" class="level-btn">Intermediate (1-3 Years)</button>
            <button type="submit" name="level" value="Advanced" class="level-btn">Advanced (3+ Years)</button>
        </form>
    </div>

</body>

</html>
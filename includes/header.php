<?php
// includes/header.php
// Smart check: Only start the session if one doesn't already exist
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nritya House | Cultural LMS</title>
    <link rel="stylesheet" href="/Nritya_House/assets/css/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

    <header>
        <div class="container">
            <nav>
                <div class="logo">
                    <i class="fa-solid fa-fire-flame-curved" style="color: #ec4899;"></i> Nritya House
                </div>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="classes.php">Classes</a></li>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="dashboard.php">Progress</a></li>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li><a href="admin_dashboard.php" style="color: #ec4899; font-weight: bold;"><i class="fa-solid fa-chart-line"></i> Admin Panel</a></li>
                    <?php endif; ?>

                    <a href="community.php">Community</a>
                </ul>
            </nav>
        </div>
    </header>

    <main style="padding: 40px 0;">
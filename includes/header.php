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
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css?v=<?php echo time(); ?>">
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
                    <li><a href="<?php echo BASE_URL; ?>/home">Home</a></li>

                    <li><a href="<?php echo BASE_URL; ?>/course">Classes</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/community">Community</a></li>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="<?php echo BASE_URL; ?>/dashboard">Dashboard</a></li>

                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li><a href="<?php echo BASE_URL; ?>/admin_dashboard" style="color: #ec4899; font-weight: bold;">
                                    <i class="fa-solid fa-chart-line"></i> Admin Panel
                                </a></li>
                        <?php endif; ?>

                        <li><a href="<?php echo BASE_URL; ?>/profile.php"><i class="fa-solid fa-user"></i> Profile</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/auth/logout" class="btn btn-outline" style="padding: 8px 15px;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo BASE_URL; ?>/auth/login" class="btn btn-outline" style="padding: 8px 15px;">Login</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/auth/register" class="btn btn-primary" style="padding: 8px 15px;">Sign Up</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main style="padding: 40px 0;">
<?php
// Intelligent Notification Check
$has_unread_notifications = false;
if (isset($_SESSION['user_id'])) {
    try {
        $db = \App\Core\Database::getInstance()->getConnection();
        // Check for unread notifications (is_read = 0 or boolean false)
        $stmt = $db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
        $stmt->execute([$_SESSION['user_id']]);
        $has_unread_notifications = ($stmt->fetchColumn() > 0);
    } catch (\PDOException $e) {
        // Failsafe: If the notifications table doesn't exist yet, don't crash the header.
        $has_unread_notifications = false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nritya House | Online Dance Academy</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css?v=<?php echo time(); ?>">
</head>

<body>

    <header class="main-header">
        <div class="container header-container">

            <a href="<?php echo BASE_URL; ?>/" class="logo-wrapper">
                <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="Nritya House Logo" class="brand-logo">
                <span class="brand-name">Nritya House</span>
            </a>

            <div id="nav-menu" class="nav-menu">

                <nav class="primary-nav">
                    <ul class="nav-links">
                        <li><a href="<?php echo BASE_URL; ?>/course">Courses</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/instructor">Masters</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/event">Events</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/community">Community</a></li>
                    </ul>
                </nav>

                <div class="action-group">
                    <?php if (isset($_SESSION['user_id'])): ?>

                        <a href="<?php echo BASE_URL; ?>/notification" class="notification-bell" title="Notifications">
                            <i class="fa-regular fa-bell"></i>
                            <?php if ($has_unread_notifications): ?>
                                <span class="bell-dot"></span>
                            <?php endif; ?>
                        </a>

                        <a href="<?php echo BASE_URL; ?>/dashboard" class="btn-dashboard">
                            <i class="fa-solid fa-gauge-high"></i> Dashboard
                        </a>

                        <div class="account-dropdown">
                            <button class="account-trigger" id="account-btn">
                                <i class="fa-solid fa-circle-user"></i>
                                <span class="user-alias"><?php echo htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]); ?></span>
                                <i class="fa-solid fa-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu" id="account-menu">
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <a href="<?php echo BASE_URL; ?>/admin"><i class="fa-solid fa-user-shield"></i> Admin Panel</a>
                                <?php endif; ?>
                                <a href="<?php echo BASE_URL; ?>/profile"><i class="fa-solid fa-user-gear"></i> My Profile</a>
                                <hr>
                                <a href="<?php echo BASE_URL; ?>/auth/logout" class="logout-link"><i class="fa-solid fa-power-off"></i> Sign Out</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>/auth/login" class="login-link">Login</a>
                        <a href="<?php echo BASE_URL; ?>/auth/register" class="btn btn-primary">Join Free</a>
                    <?php endif; ?>
                </div>
            </div>

            <button id="mobile-menu-btn" class="menu-toggle">
                <i class="fa-solid fa-bars"></i>
            </button>

        </div>
    </header>
    <main>
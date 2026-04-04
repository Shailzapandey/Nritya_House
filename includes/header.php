<?php
// includes/header.php
// Smart check: Only start the session if one doesn't already exist
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize notifications globally for the header
$unreadNotifs = [];
if (isset($_SESSION['user_id'])) {
    $notifModel = new \App\Models\Notification();
    $unreadNotifs = $notifModel->getUnread($_SESSION['user_id']);
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
                    <li><a href="<?php echo BASE_URL; ?>/instructor">Instructors</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/event">Events</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/community">Community</a></li>

                    <?php if (isset($_SESSION['user_id'])): ?>

                        <li style="position: relative;" class="dropdown-trigger">
                            <a href="#" style="position: relative; display: inline-block;">
                                <i class="fa-solid fa-bell" style="font-size: 1.2rem;"></i>
                                <?php if (count($unreadNotifs) > 0): ?>
                                    <span style="position: absolute; top: -8px; right: -12px; background: #ef4444; color: white; font-size: 0.7rem; font-weight: bold; padding: 2px 6px; border-radius: 50%;">
                                        <?php echo count($unreadNotifs); ?>
                                    </span>
                                <?php endif; ?>
                            </a>

                            <div class="dropdown-menu" style="display: none; position: absolute; right: 0; top: 100%; width: 300px; background: white; border-radius: var(--radius-md); box-shadow: var(--shadow-md); z-index: 100; border: 1px solid #eee; overflow: hidden;">
                                <div style="padding: 10px 15px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; font-weight: bold; color: var(--text-dark);">Notifications</div>
                                <?php if (empty($unreadNotifs)): ?>
                                    <div style="padding: 15px; text-align: center; color: var(--text-muted); font-size: 0.9rem;">You're all caught up!</div>
                                <?php else: ?>
                                    <div style="max-height: 300px; overflow-y: auto;">
                                        <?php foreach ($unreadNotifs as $notif): ?>
                                            <a href="<?php echo BASE_URL; ?>/notification/read?id=<?php echo $notif['notification_id']; ?>&ref=<?php echo urlencode($notif['link']); ?>" style="display: block; padding: 12px 15px; border-bottom: 1px solid #f1f5f9; text-decoration: none; color: var(--text-dark); font-size: 0.9rem; transition: background 0.2s;">
                                                <div style="color: var(--primary); margin-bottom: 5px;"><i class="fa-solid fa-circle-info"></i> <?php echo htmlspecialchars($notif['message']); ?></div>
                                                <div style="font-size: 0.75rem; color: var(--text-muted);"><?php echo date('M d, g:i A', strtotime($notif['created_at'])); ?></div>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </li>

                        <script>
                            document.querySelector('.dropdown-trigger > a').addEventListener('click', function(e) {
                                e.preventDefault();
                                const menu = this.nextElementSibling;
                                menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
                            });
                        </script>

                        <li><a href="<?php echo BASE_URL; ?>/dashboard">Dashboard</a></li>

                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li><a href="<?php echo BASE_URL; ?>/admin/courses" style="color: #ec4899; font-weight: bold;">
                                    <i class="fa-solid fa-shield-halved"></i> Admin Panel
                                </a></li>
                        <?php endif; ?>

                        <li><a href="<?php echo BASE_URL; ?>/profile"><i class="fa-solid fa-user"></i> Profile</a></li>
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
<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container" style="max-width: 800px; margin: 60px auto; min-height: 50vh;">

    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 30px;">
        <h1 style="font-size: 2rem; color: var(--text-dark);">Notifications</h1>
        <button class="btn btn-outline" style="padding: 5px 15px; font-size: 0.85rem;">Mark all as read</button>
    </div>

    <div style="display: flex; flex-direction: column; gap: 15px;">
        <?php if (empty($notifications)): ?>

            <div style="text-align: center; padding: 60px 20px; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); border: 1px dashed #cbd5e1;">
                <i class="fa-regular fa-bell-slash" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 20px;"></i>
                <h3 style="color: var(--text-dark); margin-bottom: 10px;">You're all caught up!</h3>
                <p style="color: var(--text-muted);">When you enroll in courses or receive community replies, they will appear here.</p>
                <a href="<?php echo BASE_URL; ?>/course" class="btn btn-primary" style="margin-top: 20px;">Browse Courses</a>
            </div>

        <?php else: ?>

            <?php foreach ($notifications as $alert): ?>
                <div style="background: white; padding: 20px; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); border-left: 4px solid <?php echo ($alert['is_read'] ? '#cbd5e1' : 'var(--primary)'); ?>; display: flex; align-items: flex-start; gap: 15px; transition: 0.2s;">

                    <div style="background: <?php echo ($alert['is_read'] ? '#f8fafc' : '#f3e8ff'); ?>; color: var(--primary); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fa-solid fa-bolt"></i>
                    </div>

                    <div>
                        <p style="color: var(--text-dark); margin-bottom: 5px; line-height: 1.5;">
                            <?php echo htmlspecialchars($alert['message'] ?? 'New notification alert.'); ?>
                        </p>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">
                            <?php echo isset($alert['created_at']) ? date('M j, Y - g:i A', strtotime($alert['created_at'])) : 'Just now'; ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
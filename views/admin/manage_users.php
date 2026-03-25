<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container" style="max-width: 1200px; margin: 40px auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="font-size: 2rem; color: var(--text-dark);">Manage Users</h2>
        <a href="<?php echo BASE_URL; ?>/admin/courses" class="btn btn-outline"><i class="fa-solid fa-book"></i> Manage Catalog</a>
    </div>

    <div style="overflow-x: auto; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);">
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                <th style="padding: 15px;">ID</th>
                <th style="padding: 15px;">Full Name</th>
                <th style="padding: 15px;">Email</th>
                <th style="padding: 15px;">Role</th>
                <th style="padding: 15px;">Joined</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr style="border-bottom: 1px solid #e2e8f0; transition: background 0.2s;">
                    <td style="padding: 15px; color: var(--text-muted);"><?php echo $user['user_id']; ?></td>
                    <td style="padding: 15px;"><strong><?php echo htmlspecialchars($user['full_name']); ?></strong></td>
                    <td style="padding: 15px;"><?php echo htmlspecialchars($user['email']); ?></td>
                    <td style="padding: 15px;">
                        <span class="badge" style="background: <?php echo $user['role'] === 'admin' ? '#ef4444' : '#3b82f6'; ?>; color: white;">
                            <?php echo htmlspecialchars(strtoupper($user['role'])); ?>
                        </span>
                    </td>
                    <td style="padding: 15px; color: var(--text-muted); font-size: 0.9rem;">
                        <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container" style="max-width: 1200px; margin: 40px auto;">

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'role_updated'): ?>
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; border: 1px solid #bbf7d0;">
            <i class="fa-solid fa-user-shield"></i> User access privileges successfully updated.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] == 'self_demotion'): ?>
        <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; border: 1px solid #fecaca;">
            <i class="fa-solid fa-triangle-exclamation"></i> Security Warning: You cannot revoke your own admin privileges.
        </div>
    <?php endif; ?>

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
                <th style="padding: 15px;">Actions</th>
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
                    <td style="padding: 15px;">

                        <form action="<?php echo BASE_URL; ?>/admin/toggleUserRole" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to change this user\'s access privileges?');">
                            <?php echo \App\Core\Security::csrfField(); ?>
                            <input type="hidden" name="target_user_id" value="<?php echo $user['user_id']; ?>">
                            <input type="hidden" name="current_role" value="<?php echo $user['role']; ?>">

                            <?php if ($user['role'] === 'admin'): ?>
                                <button type="submit" class="btn" style="background: white; border: 1px solid #ef4444; color: #ef4444; padding: 6px 12px; font-size: 0.85rem; cursor: pointer; transition: 0.2s;">
                                    Revoke Admin
                                </button>
                            <?php else: ?>
                                <button type="submit" class="btn" style="background: #10b981; border: 1px solid #10b981; color: white; padding: 6px 12px; font-size: 0.85rem; cursor: pointer; transition: 0.2s;">
                                    Make Admin
                                </button>
                            <?php endif; ?>
                        </form>

                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php if (empty($users)): ?>
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                <i class="fa-solid fa-users-slash" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.5;"></i>
                <p>No users found in the system.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

// 1. STRICT SUPER-ADMIN GUARD
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access. Admin privileges required.");
}

try {
    // Fetch all users
    $stmt = $pdo->query("SELECT user_id, full_name, email, role, created_at FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

include_once 'includes/header.php';
?>

<div class="container" style="max-width: 1000px; margin: 40px auto; background: var(--bg-card); padding: 30px; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid var(--bg-main); padding-bottom: 10px;">
        <h2 style="margin: 0;">User Management</h2>
        <a href="admin_dashboard.php" class="btn btn-outline" style="padding: 8px 16px; font-size: 0.9rem;">Back to Dashboard</a>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'role_updated'): ?>
        <div style="background: #dcfce7; color: #166534; padding: 10px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #bbf7d0; text-align: center; font-weight: bold;">
            User role successfully updated.
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['error']) && $_GET['error'] == 'self_demotion'): ?>
        <div style="background: #fee2e2; color: #dc2626; padding: 10px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f87171; text-align: center; font-weight: bold;">
            Security Protocol: You cannot revoke your own Admin privileges.
        </div>
    <?php endif; ?>

    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="background: var(--bg-main); border-bottom: 2px solid #e5e7eb;">
                <th style="padding: 12px; font-weight: 600;">Name</th>
                <th style="padding: 12px; font-weight: 600;">Email</th>
                <th style="padding: 12px; font-weight: 600;">Current Role</th>
                <th style="padding: 12px; font-weight: 600;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr style="border-bottom: 1px solid #f3f4f6;">
                    <td style="padding: 12px;"><?php echo htmlspecialchars($user['full_name']); ?></td>
                    <td style="padding: 12px; color: var(--text-muted);"><?php echo htmlspecialchars($user['email']); ?></td>
                    <td style="padding: 12px;">
                        <?php if ($user['role'] === 'admin'): ?>
                            <span class="badge" style="background: #dbeafe; color: #1e40af;">Admin</span>
                        <?php else: ?>
                            <span class="badge" style="background: #f3f4f6; color: #4b5563;">Student</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 12px;">
                        <form action="actions/toggle_role.php" method="POST" style="margin: 0;">
                            <input type="hidden" name="target_user_id" value="<?php echo $user['user_id']; ?>">
                            <input type="hidden" name="current_role" value="<?php echo $user['role']; ?>">

                            <?php if ($user['role'] === 'admin'): ?>
                                <button type="submit" class="btn" style="background: #ef4444; color: white; padding: 6px 12px; font-size: 0.8rem;">Revoke Admin</button>
                            <?php else: ?>
                                <button type="submit" class="btn" style="background: #10b981; color: white; padding: 6px 12px; font-size: 0.8rem;">Make Admin</button>
                            <?php endif; ?>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include_once 'includes/footer.php'; ?>
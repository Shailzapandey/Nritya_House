<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container" style="max-width: 600px; margin: 40px auto; background: var(--bg-card); padding: 30px; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);">
    <h2 style="margin-bottom: 20px; border-bottom: 2px solid var(--bg-main); padding-bottom: 10px;">My Profile</h2>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
        <div style="background: #dcfce7; color: #166534; padding: 10px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #bbf7d0; text-align: center; font-weight: bold;">
            Profile updated successfully.
        </div>
    <?php endif; ?>

    <div style="margin-bottom: 20px;">
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 5px;">Name</p>
        <p style="font-size: 1.1rem; font-weight: 500;"><?php echo htmlspecialchars($user['full_name']); ?></p>
    </div>

    <div style="margin-bottom: 20px;">
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 5px;">Email</p>
        <p style="font-size: 1.1rem; font-weight: 500;"><?php echo htmlspecialchars($user['email']); ?></p>
    </div>

    <form action="<?php echo BASE_URL; ?>/profile/update" method="POST">
        <?php echo \App\Core\Security::csrfField(); ?>

        <div style="margin-bottom: 25px;">
            <label style="display: block; font-weight: bold; margin-bottom: 10px;">Dance Experience Level</label>
            <select name="experience_level" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: var(--radius-md); font-size: 1rem;">
                <option value="Beginner" <?php echo ($user['experience_level'] == 'Beginner') ? 'selected' : ''; ?>>Beginner</option>
                <option value="Intermediate" <?php echo ($user['experience_level'] == 'Intermediate') ? 'selected' : ''; ?>>Intermediate</option>
                <option value="Advanced" <?php echo ($user['experience_level'] == 'Advanced') ? 'selected' : ''; ?>>Advanced</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Save Changes</button>
    </form>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
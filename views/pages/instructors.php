<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container" style="max-width: 1200px; margin: 40px auto;">
    <div style="text-align: center; margin-bottom: 50px;">
        <h1 style="font-size: 2.5rem; margin-bottom: 10px;">Meet Our Masters</h1>
        <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Learn from world-renowned dancers and choreographers dedicated to elevating your craft.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
        <?php foreach ($instructors as $inst): ?>
            <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); overflow: hidden; text-align: center; transition: transform 0.2s; border: 1px solid #eee;">

                <div style="padding: 30px 20px 10px 20px;">
                    <img src="<?php echo BASE_URL . '/assets/images/' . htmlspecialchars($inst['profile_image_url']); ?>"
                        alt="<?php echo htmlspecialchars($inst['full_name']); ?>"
                        style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid var(--primary-light); margin-bottom: 15px; box-shadow: var(--shadow-sm);">

                    <h3 style="margin: 0 0 5px 0; font-size: 1.4rem; color: var(--text-dark);"><?php echo htmlspecialchars($inst['full_name']); ?></h3>
                    <p style="color: var(--primary); font-weight: bold; margin-bottom: 15px; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;">
                        <?php echo htmlspecialchars($inst['specialty']); ?>
                    </p>

                    <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.5; margin-bottom: 20px;">
                        <?php echo htmlspecialchars(substr($inst['bio'], 0, 100)) . '...'; ?>
                    </p>
                </div>

                <div style="background: #f8fafc; padding: 15px; border-top: 1px solid #eee;">
                    <a href="<?php echo BASE_URL; ?>/instructor/profile?id=<?php echo $inst['instructor_id']; ?>" class="btn btn-outline" style="width: 100%;">View Full Profile</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
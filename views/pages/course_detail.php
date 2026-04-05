<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container" style="max-width: 1100px; margin: 40px auto;">
    <div class="course-detail-layout" style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px;">
        <div>
            <nav style="margin-bottom: 20px; font-size: 0.9rem; color: var(--text-muted);">
                <a href="<?php echo BASE_URL; ?>/course" style="color: inherit; text-decoration: none;">Courses</a> /
                <span style="color: var(--text-dark);"><?php echo htmlspecialchars($course['title']); ?></span>
            </nav>

            <h1 style="font-size: 2.5rem; margin-bottom: 15px;"><?php echo htmlspecialchars($course['title']); ?></h1>

            <p style="font-size: 1.2rem; color: var(--text-muted); margin-bottom: 30px; line-height: 1.6;">
                <?php echo htmlspecialchars($course['description'] ?? 'No description available for this course yet. Master the art of ' . $course['style'] . ' with our professional masters.'); ?>
            </p>

            <div style="display: flex; gap: 30px; margin: 30px 0; padding: 20px; background: #f8fafc; border-radius: var(--radius-md);">
                <div>
                    <p style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px;">Instructor</p>
                    <strong><?php echo htmlspecialchars($course['instructor_name']); ?></strong>
                </div>
                <div>
                    <p style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px;">Level</p>
                    <strong><?php echo htmlspecialchars($course['difficulty_level']); ?></strong>
                </div>
                <div>
                    <p style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px;">Duration</p>
                    <strong><?php echo htmlspecialchars($course['duration_weeks']); ?> Weeks</strong>
                </div>
            </div>

            <h3 style="margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px;">What you'll learn</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 40px;">
                <div style="display: flex; gap: 10px;"><i class="fa-solid fa-check" style="color: #10b981;"></i> Fundamental footwork</div>
                <div style="display: flex; gap: 10px;"><i class="fa-solid fa-check" style="color: #10b981;"></i> Advanced rhythm control</div>
                <div style="display: flex; gap: 10px;"><i class="fa-solid fa-check" style="color: #10b981;"></i> Performance stage presence</div>
                <div style="display: flex; gap: 10px;"><i class="fa-solid fa-check" style="color: #10b981;"></i> Choreography creation</div>
            </div>
        </div>

        <div style="position: sticky; top: 20px; height: fit-content;">
            <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); overflow: hidden; border: 1px solid #e2e8f0;">
                <img src="<?php echo BASE_URL . '/assets/images/' . ($course['thumbnail_url'] ?: 'default_thumb.jpg'); ?>" style="width: 100%; aspect-ratio: 16/9; object-fit: cover;">
                <div style="padding: 25px;">
                    <div style="font-size: 2rem; font-weight: 800; margin-bottom: 20px;">Rs.1500</div>

                    <?php if (isset($_SESSION['user_id']) && $has_purchased): ?>
                        <a href="<?php echo BASE_URL; ?>/lesson/view?class_id=<?php echo $course['class_id']; ?>" class="btn btn-primary" style="width: 100%; display: block; text-align: center; padding: 15px;">Go to Classroom</a>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>/checkout?class_id=<?php echo $course['class_id']; ?>" class="btn btn-primary" style="width: 100%; display: block; text-align: center; padding: 15px; margin-bottom: 10px;">Buy Now</a>
                        <a href="<?php echo BASE_URL; ?>/lesson/view?class_id=<?php echo $course['class_id']; ?>" class="btn btn-outline" style="width: 100%; display: block; text-align: center; padding: 15px;">Free Preview</a>
                    <?php endif; ?>

                    <p style="text-align: center; font-size: 0.8rem; color: var(--text-muted); margin-top: 15px;">30-Day Money-Back Guarantee</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
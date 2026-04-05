<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container" style="max-width: 1000px; margin: 40px auto;">

    <div style="margin-bottom: 30px;">
        <a href="<?php echo BASE_URL; ?>/instructor" class="btn btn-outline" style="padding: 8px 15px; font-size: 0.9rem;"><i class="fa-solid fa-arrow-left"></i> Back to Instructors</a>
    </div>

    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); overflow: hidden; display: flex; flex-wrap: wrap; margin-bottom: 40px;">
        <div style="flex: 1; min-width: 300px; background: #000;">
            <img src="<?php echo BASE_URL . '/assets/images/' . htmlspecialchars($instructor['profile_image_url']); ?>" alt="<?php echo htmlspecialchars($instructor['full_name']); ?>" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.9;">
        </div>
        <div style="flex: 2; padding: 40px; min-width: 300px;">
            <h1 style="font-size: 2.5rem; margin-bottom: 5px;"><?php echo htmlspecialchars($instructor['full_name']); ?></h1>
            <p style="color: var(--primary); font-weight: bold; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px;"><?php echo htmlspecialchars($instructor['specialty']); ?></p>

            <h3 style="border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">Biography</h3>
            <p style="color: var(--text-muted); line-height: 1.8;">
                <?php echo nl2br(htmlspecialchars($instructor['bio'])); ?>
            </p>
        </div>
    </div>

    <h2 style="margin-bottom: 20px;">Courses by <?php echo htmlspecialchars($instructor['full_name']); ?></h2>

    <div class="course-grid">
        <?php if (empty($courses)): ?>
            <p style="color: var(--text-muted); grid-column: 1/-1;">This instructor has not published any courses yet.</p>
        <?php else: ?>
            <?php foreach ($courses as $course): ?>
                <div class="course-card">
                    <?php $thumb_path = !empty($course['thumbnail_url']) ? 'assets/images/' . $course['thumbnail_url'] : 'assets/images/default_thumb.jpg'; ?>
                    <img src="<?php echo BASE_URL . '/' . htmlspecialchars($thumb_path); ?>" class="course-thumb">
                    <div class="course-content">
                        <span class="badge <?php echo ($course['difficulty_level'] == 'Beginner') ? 'badge-beginner' : (($course['difficulty_level'] == 'Intermediate') ? 'badge-intermediate' : 'badge-advanced'); ?>" style="margin-bottom: 10px;">
                            <?php echo htmlspecialchars($course['difficulty_level']); ?>
                        </span>
                        <h3 style="margin: 0 0 10px 0;"><?php echo htmlspecialchars($course['title']); ?></h3>
                        <a href="<?php echo BASE_URL; ?>/lesson/view?class_id=<?php echo $course['class_id']; ?>" class="btn btn-outline" style="margin-top: auto;">View Course</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container">
    <h2 style="margin-bottom: 30px;">Course Catalog</h2>

    <div class="course-grid">
        <?php foreach ($courses as $course): ?>
            <div class="course-card">

                <?php $thumb_path = !empty($course['thumbnail_url']) ? 'assets/images/' . $course['thumbnail_url'] : 'assets/images/default_thumb.jpg'; ?>
                <img src="<?php echo BASE_URL . '/' . htmlspecialchars($thumb_path); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" class="course-thumb">

                <div class="course-content">

                    <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 15px;">
                        <?php if ($course['is_recommended'] == 1): ?>
                            <span class="badge badge-recommend"><i class="fa-solid fa-star"></i> Recommended</span>
                        <?php endif; ?>

                        <?php
                        $lvl_class = 'badge-beginner';
                        if ($course['difficulty_level'] == 'Intermediate') $lvl_class = 'badge-intermediate';
                        if ($course['difficulty_level'] == 'Advanced') $lvl_class = 'badge-advanced';
                        ?>
                        <span class="badge <?php echo $lvl_class; ?>"><?php echo htmlspecialchars($course['difficulty_level']); ?></span>
                    </div>

                    <h3 style="margin: 0 0 10px 0; font-size: 1.25rem;"><?php echo htmlspecialchars($course['title']); ?></h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 20px;">
                        <i class="fa-solid fa-chalkboard-user"></i> <?php echo htmlspecialchars($course['instructor']); ?>
                    </p>

                    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; font-size: 0.85rem; color: var(--text-muted); font-weight: 500;">
                        <span><i class="fa-solid fa-music"></i> <?php echo htmlspecialchars($course['style']); ?></span>
                        <span><i class="fa-regular fa-clock"></i> <?php echo htmlspecialchars($course['duration_min']); ?> min</span>
                    </div>

                    <div style="margin-top: auto;">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="<?php echo BASE_URL; ?>/lesson/view?class_id=<?php echo $course['class_id']; ?>" class="btn btn-primary" style="width: 100%;">Go to Course</a>
                        <?php else: ?>
                            <a href="<?php echo BASE_URL; ?>/lesson?class_id=<?php echo $course['class_id']; ?>" class="btn btn-outline" style="width: 100%;">Start Free Preview</a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (count($courses) === 0): ?>
        <p>No classes are currently available.</p>
    <?php endif; ?>

</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
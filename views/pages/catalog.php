<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container" style="max-width: 1200px; margin: 40px auto;">

    <div style="text-align: center; margin-bottom: 50px;">
        <h1 style="font-size: 2.5rem; margin-bottom: 10px; color: var(--text-dark);">Explore Our Catalog</h1>
        <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
            Professional dance instruction from world-class masters. Start your journey today.
        </p>
    </div>

    <div class="course-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">

        <?php if (empty($courses)): ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 100px 0; background: #f8fafc; border-radius: var(--radius-lg);">
                <i class="fa-solid fa-layer-group" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 20px;"></i>
                <h3 style="color: var(--text-dark);">No courses found.</h3>
                <p style="color: var(--text-muted);">We are currently updating our curriculum. Check back soon!</p>
            </div>
        <?php else: ?>
            <?php foreach ($courses as $course): ?>
                <?php
                // Construct thumbnail path
                $thumb_path = !empty($course['thumbnail_url']) ? 'assets/images/' . $course['thumbnail_url'] : 'assets/images/default_thumb.jpg';

                // Difficulty Class Mapping
                $difficulty_class = 'badge-beginner';
                if ($course['difficulty_level'] == 'Intermediate') $difficulty_class = 'badge-intermediate';
                if ($course['difficulty_level'] == 'Advanced') $difficulty_class = 'badge-advanced';
                ?>

                <div class="course-card" style="background: white; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm); transition: transform 0.3s ease, box-shadow 0.3s ease; display: flex; flex-direction: column; border: 1px solid #e2e8f0;">

                    <div style="position: relative; aspect-ratio: 16/9; overflow: hidden; background: #000;">
                        <img src="<?php echo BASE_URL . '/' . htmlspecialchars($thumb_path); ?>"
                            alt="<?php echo htmlspecialchars($course['title']); ?>"
                            style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                        <span class="badge <?php echo $difficulty_class; ?>" style="position: absolute; top: 15px; right: 15px;">
                            <?php echo htmlspecialchars($course['difficulty_level']); ?>
                        </span>
                    </div>

                    <div class="course-content" style="padding: 20px; flex-grow: 1; display: flex; flex-direction: column;">
                        <h3 style="margin: 0 0 10px 0; font-size: 1.25rem; line-height: 1.4; color: var(--text-dark);">
                            <?php echo htmlspecialchars($course['title']); ?>
                        </h3>

                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 15px;">
                            <i class="fa-solid fa-user-tie" style="margin-right: 5px;"></i>
                            Instructor: <strong><?php echo htmlspecialchars($course['instructor_name']); ?></strong>
                        </p>

                        <div style="display: flex; gap: 15px; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 20px;">
                            <span><i class="fa-solid fa-clock"></i> <?php echo htmlspecialchars($course['duration_weeks']); ?> Weeks</span>
                            <span><i class="fa-solid fa-video"></i> <?php echo htmlspecialchars($course['style']); ?></span>
                        </div>

                        <div style="margin-top: auto; display: flex; justify-content: space-between; align-items: center; padding-top: 15px; border-top: 1px solid #f1f5f9;">
                            <span style="font-size: 1.2rem; font-weight: 800; color: var(--text-dark);">Rs. 1500

                            </span>

                            <a href="<?php echo BASE_URL; ?>/course/show?id=<?php echo $course['class_id']; ?>"
                                class="btn btn-outline"
                                style="padding: 8px 15px; font-size: 0.85rem;">
                                View Details <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Adding subtle hover effects for the catalog cards */
    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .course-card:hover img {
        transform: scale(1.05);
    }
</style>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
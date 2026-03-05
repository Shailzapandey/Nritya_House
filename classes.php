<?php
// 1. Bring in the database connection
require_once 'config/database.php';

// 2. Bring in the top UI shell
include_once 'includes/header.php';

// 3. THE LOGIC (Backend)
if (isset($_SESSION['user_id'])) {
    // Logged In: Run the Recommendation Engine
    $user_id = $_SESSION['user_id'];
    $level_stmt = $pdo->prepare("SELECT experience_level FROM users WHERE user_id = ?");
    $level_stmt->execute([$user_id]);
    $user_level = $level_stmt->fetchColumn();

    $sql = "SELECT *, (difficulty_level = ?) AS is_recommended FROM classes ORDER BY is_recommended DESC, class_id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_level]);
} else {
    // Guest User: Standard chronological catalog
    $sql = "SELECT *, 0 AS is_recommended FROM classes ORDER BY class_id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container">
    <h2 style="margin-bottom: 30px;">Course Catalog</h2>

    <div class="course-grid">
        <?php foreach ($courses as $course): ?>
            <div class="course-card">

                <?php $thumb_path = !empty($course['thumbnail_url']) ? 'assets/images/' . $course['thumbnail_url'] : 'assets/images/default_thumb.jpg'; ?>
                <img src="<?php echo htmlspecialchars($thumb_path); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" class="course-thumb">

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
                            <form action="actions/enroll.php" method="POST">
                                <input type="hidden" name="class_id" value="<?php echo $course['class_id']; ?>">
                                <button type="submit" class="btn btn-primary" style="width: 100%;">Enroll Now</button>
                            </form>
                        <?php else: ?>
                            <a href="login.php" class="btn" style="background: #f3f4f6; color: var(--text-dark); width: 100%;">Log in to Enroll</a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    // 6. ERROR HANDLING
    // What if the database is empty? We tell the user.
    if (count($courses) === 0) {
        echo "<p>No classes are currently available.</p>";
    }
    ?>

</div>
</div>

<?php
// 7. Bring in the bottom UI shell
include_once 'includes/footer.php';
?>
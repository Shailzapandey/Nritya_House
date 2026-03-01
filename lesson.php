<?php
// lesson.php
session_start();
require_once 'config/database.php';
include_once 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 1. Get the class ID from the URL
if (!isset($_GET['id'])) {
    die("No class selected.");
}
$class_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

try {
    // 2. Security Check: Is the user actually enrolled in this class?
    // We do NOT want users typing "?id=5" in the URL and bypassing enrollment.
    $check_stmt = $pdo->prepare("SELECT progress_percent FROM enrollments WHERE user_id = ? AND class_id = ?");
    $check_stmt->execute([$user_id, $class_id]);

    if ($check_stmt->rowCount() == 0) {
        die("You are not enrolled in this class. <a href='classes.php'>Go to Catalog</a>");
    }

    $enrollment_data = $check_stmt->fetch(PDO::FETCH_ASSOC);
    $current_progress = $enrollment_data['progress_percent'];

    // 3. Fetch the class details
    $class_stmt = $pdo->prepare("SELECT * FROM classes WHERE class_id = ?");
    $class_stmt->execute([$class_id]);
    $class = $class_stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error loading lesson: " . $e->getMessage());
}
?>

<div class="container" style="max-width: 800px; margin: 20px auto;">
    <h2 style="margin-bottom: 10px;"><?php echo htmlspecialchars($class['title']); ?></h2>
    <p style="color: #666; margin-bottom: 20px;">Instructor: <?php echo htmlspecialchars($class['instructor']); ?></p>

    <div style="width: 100%; aspect-ratio: 16/9; background: #000; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.5); margin-bottom: 30px;">

        <?php if (!empty($class['video_url'])): ?>
            <iframe
                width="100%"
                height="100%"
                src="<?php echo htmlspecialchars($class['video_url']); ?>"
                title="<?php echo htmlspecialchars($class['title']); ?> Video Player"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        <?php else: ?>
            <div style="width: 100%; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; color: #9ca3af;">
                <i class="fa-solid fa-video-slash" style="font-size: 3rem; margin-bottom: 15px;"></i>
                <p style="font-family: monospace;">ERR_NO_MEDIA: Video stream unavailable.</p>
            </div>
        <?php endif; ?>

    </div>

    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h4 style="margin-bottom: 5px;">Current Progress: <?php echo $current_progress; ?>%</h4>
            <p style="font-size: 0.85rem; color: #666;">Watch the video and mark it complete to update your dashboard.</p>
        </div>

        <?php if ($current_progress < 100): ?>
            <form action="actions/update_progress.php" method="POST">
                <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                <button type="submit" style="padding: 10px 20px; background: #22c55e; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                    <i class="fa-solid fa-check"></i> Mark Lesson Complete (+25%)
                </button>
            </form>
        <?php else: ?>
            <span style="padding: 10px 20px; background: #e5e7eb; color: #166534; border-radius: 4px; font-weight: bold;">
                Course Completed! 🎉
            </span>
        <?php endif; ?>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>
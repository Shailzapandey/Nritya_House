<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

if (!isset($_GET['class_id'])) {
    die("Error: No class selected.");
}
$class_id = $_GET['class_id'];

// Fetch course details to show the admin what they are adding to
$stmt = $pdo->prepare("SELECT title FROM classes WHERE class_id = ?");
$stmt->execute([$class_id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) die("Course not found.");

include_once 'includes/header.php';
?>

<div class="container" style="max-width: 600px; margin: 40px auto; background: var(--bg-card); padding: 30px; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);">
    <h2 style="margin-top: 0; margin-bottom: 5px;">Add Lesson to Syllabus</h2>
    <p style="color: var(--primary); font-weight: bold; margin-bottom: 20px;">Course: <?php echo htmlspecialchars($course['title']); ?></p>

    <form action="actions/add_lesson_process.php" method="POST">
        <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Lesson Title (e.g., 'Part 1: Footwork')</label>
            <input type="text" name="lesson_title" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">YouTube Embed URL</label>
            <input type="url" name="video_url" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" placeholder="https://www.youtube.com/embed/...">
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Order Index (1, 2, 3...)</label>
            <input type="number" name="order_index" value="1" min="1" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Save Lesson</button>
    </form>
</div>

<?php include_once 'includes/footer.php'; ?>
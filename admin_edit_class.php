<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

// 1. STRICT ADMIN GUARD
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access. Admin privileges required.");
}

// 2. FETCH EXISTING DATA
if (!isset($_GET['id'])) {
    die("Error: No class ID provided.");
}

$class_id = $_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM classes WHERE class_id = ?");
    $stmt->execute([$class_id]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$course) {
        die("Error: Class not found in the database.");
    }
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

include_once 'includes/header.php';
?>

<div class="container" style="max-width: 800px; margin: 40px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <h2 style="margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px;">Edit Class Details</h2>

    <form action="actions/edit_class_process.php" method="POST">
        <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($course['class_id']); ?>">
        <input type="hidden" name="original_last_updated" value="<?php echo htmlspecialchars($course['last_updated'] ?? ''); ?>">

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Course Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($course['title'] ?? ''); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Instructor Name</label>
            <input type="text" name="instructor" value="<?php echo htmlspecialchars($course['instructor'] ?? ''); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Difficulty Level</label>
            <select name="difficulty_level" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                <option value="Beginner" <?php echo ($course['difficulty_level'] == 'Beginner') ? 'selected' : ''; ?>>Beginner</option>
                <option value="Intermediate" <?php echo ($course['difficulty_level'] == 'Intermediate') ? 'selected' : ''; ?>>Intermediate</option>
                <option value="Advanced" <?php echo ($course['difficulty_level'] == 'Advanced') ? 'selected' : ''; ?>>Advanced</option>
            </select>
        </div>

        <div style="margin-top: 20px; text-align: right;">
            <a href="admin_classes.php" style="padding: 10px 20px; text-decoration: none; color: #666; margin-right: 15px;">Cancel</a>
            <button type="submit" style="background: #3b82f6; color: white; padding: 10px 20px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                Save Changes <i class="fa-solid fa-save"></i>
            </button>
        </div>
    </form>
</div>

<?php include_once 'includes/footer.php'; ?>
<?php
// dashboard.php (Top PHP Block)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

// Kick out guests
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

try {
    // THE PERSONALIZATION ENGINE: Only fetch classes THIS user is enrolled in
    $sql = "SELECT c.* FROM classes c 
            INNER JOIN enrollments e ON c.class_id = e.class_id 
            WHERE e.user_id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $enrolled_classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

// 4. THE UI SHELL
include_once 'includes/header.php';
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>Welcome back, <span style="color: var(--primary);"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span></h2>
        <a href="logout.php" style="padding: 8px 16px; border: 1px solid #ef4444; color: #ef4444; text-decoration: none; border-radius: 4px;">Log Out</a>
    </div>
    <?php if (isset($_GET['msg'])): ?>
        <?php if ($_GET['msg'] == 'enrolled'): ?>
            <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <i class="fa-solid fa-circle-check"></i> Enrollment successful! You can now start learning.
            </div>
        <?php elseif ($_GET['msg'] == 'already_enrolled'): ?>
            <div style="background: #fef08a; color: #854d0e; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <i class="fa-solid fa-circle-exclamation"></i> You are already enrolled in that class.
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'enrollment_success'): ?>
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            Successfully enrolled in the new class!
        </div>
    <?php endif; ?>

    <h3 style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #eee;">My Learning Progress</h3>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-top: 20px;">
        <?php if (empty($enrolled_classes)): ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 50px; background: white; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                <i class="fa-solid fa-folder-open" style="font-size: 3rem; color: #ccc; margin-bottom: 15px;"></i>
                <h3 style="color: #666;">No active enrollments.</h3>
                <p style="margin-top: 10px;"><a href="classes.php" style="color: #ec4899; text-decoration: none; font-weight: bold;">Browse the catalog</a> to start learning.</p>
            </div>
        <?php else: ?>

            <?php foreach ($enrolled_classes as $course): ?>
                <div class="card" style="background: white; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); overflow: hidden; display: flex; flex-direction: column;">
                    <?php $thumb = !empty($course['thumbnail_url']) ? 'assets/images/' . $course['thumbnail_url'] : 'assets/images/default_thumb.jpg'; ?>
                    <img src="<?php echo htmlspecialchars($thumb); ?>" style="width: 100%; height: 200px; object-fit: cover;">

                    <div style="padding: 20px; display: flex; flex-direction: column; flex-grow: 1;">
                        <h3 style="margin-top: 0;"><?php echo htmlspecialchars($course['title']); ?></h3>
                        <p style="color: #666; font-size: 0.9rem;">Instructor: <?php echo htmlspecialchars($course['instructor']); ?></p>

                        <div style="margin-top: auto;">
                            <a href="lesson.php?id=<?php echo $course['class_id']; ?>" style="display: block; text-align: center; width: 100%; padding: 10px; margin-top: 15px; background: #22c55e; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">
                                <i class="fa-solid fa-play"></i> Continue Learning
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>

    </div>
</div>

<?php
// 5. CLOSE THE UI SHELL
include_once 'includes/footer.php';
?>
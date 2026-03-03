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
// --- THE LEVEL ASSESSMENT CHOKE-POINT ---
// Note: Verify if your primary key column in the users table is 'id' or 'user_id' and update the WHERE clause if necessary.
$level_check = $pdo->prepare("SELECT experience_level FROM users WHERE user_id = ?");
$level_check->execute([$user_id]);
$user_level = $level_check->fetchColumn();

if (empty($user_level)) {
    // The user has no level assigned. Force them to the assessment.
    header("Location: assessment.php");
    exit;
}
try {
    // 1. Fetch Enrolled Classes
    $sql = "SELECT c.* FROM classes c 
            INNER JOIN enrollments e ON c.class_id = e.class_id 
            WHERE e.user_id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $enrolled_classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. THE STREAK ENGINE MATH
    $streak_stmt = $pdo->prepare("SELECT activity_date FROM user_activity_logs WHERE user_id = ? ORDER BY activity_date DESC");
    $streak_stmt->execute([$user_id]);
    $activity_dates = $streak_stmt->fetchAll(PDO::FETCH_COLUMN); // Fetches a flat array of dates

    $current_streak = 0;

    if (!empty($activity_dates)) {
        $today = new DateTime('today');
        $yesterday = new DateTime('yesterday');
        $first_log = new DateTime($activity_dates[0]);

        // A streak is only alive if they logged activity today OR yesterday
        if ($first_log == $today || $first_log == $yesterday) {
            $expected_date = $first_log;

            foreach ($activity_dates as $date_string) {
                $log_date = new DateTime($date_string);

                if ($log_date == $expected_date) {
                    $current_streak++;
                    $expected_date->modify('-1 day'); // Step backwards in time
                } else {
                    break; // The chain is broken
                }
            }
        }
    }
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

// 3. THE UI SHELL
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

    <div style="background: linear-gradient(135deg, #a855f7, #ec4899); color: white; padding: 20px; border-radius: 8px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div>
            <h2 style="margin: 0; font-size: 1.5rem;">Welcome back, <?php echo htmlspecialchars($user_name); ?>!</h2>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">Consistency is the key to mastering the art.</p>
        </div>
        <div style="text-align: center; background: rgba(255,255,255,0.2); padding: 10px 20px; border-radius: 8px;">
            <div style="font-size: 2rem; font-weight: bold; line-height: 1;">
                🔥 <?php echo $current_streak; ?>
            </div>
            <div style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin-top: 5px;">
                Day Streak
            </div>
        </div>
    </div>

    <h3 style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #eee;">My Learning Progress</h3>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px; margin-top: 20px;">

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
// 4. CLOSE THE UI SHELL
include_once 'includes/footer.php';
?>
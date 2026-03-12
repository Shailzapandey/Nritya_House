<?php
// lesson.php (MASTER INTEGRATED VERSION)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';



if (!isset($_GET['class_id'])) {
    die("Error: No class selected.");
}
$class_id = $_GET['class_id'];

try {
    // 2. Query 1: Fetch the Course Container details
    $stmt_course = $pdo->prepare("SELECT title FROM classes WHERE class_id = ?");
    $stmt_course->execute([$class_id]);
    $course = $stmt_course->fetch(PDO::FETCH_ASSOC);

    if (!$course) die("Course not found.");

    // 3. Query 2: Fetch the Syllabus (One-to-Many Relational Query)
    $stmt_lessons = $pdo->prepare("SELECT * FROM lessons WHERE class_id = ? ORDER BY order_index ASC");
    $stmt_lessons->execute([$class_id]);
    $lessons = $stmt_lessons->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

// 4. The Active Lesson Engine
$active_lesson = null;
if (!empty($lessons)) {
    if (isset($_GET['lesson_id'])) {
        $lesson_id = $_GET['lesson_id'];
        foreach ($lessons as $lesson) {
            if ($lesson['lesson_id'] == $lesson_id) {
                $active_lesson = $lesson;
                break;
            }
        }
    }
    if (!$active_lesson) {
        $active_lesson = $lessons[0];
    }
}

// 5. Master YouTube ID Extractor (Immune to dirty database URLs)
$youtube_video_id = '';
if ($active_lesson && !empty($active_lesson['video_url'])) {
    $url = $active_lesson['video_url'];
    // Industry-standard regex to capture exactly 11 characters, regardless of the link format
    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $url, $match);

    if (isset($match[1])) {
        $youtube_video_id = $match[1];
    } else {
        // Absolute fallback just in case
        $parts = explode('/', rtrim($url, '/'));
        $youtube_video_id = end($parts);
    }
}

include_once 'includes/header.php';
?>

<div class="container" style="max-width: 1200px; margin: 40px auto;">

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">

        <div style="grid-column: span 2;">
            <h1 style="margin-bottom: 10px; font-size: 2rem;"><?php echo htmlspecialchars($course['title']); ?></h1>

            <?php if ($active_lesson): ?>
                <h3 style="color: var(--primary); margin-bottom: 20px;">
                    <i class="fa-solid fa-play"></i> <?php echo htmlspecialchars($active_lesson['lesson_title']); ?>
                    <?php if (!isset($_SESSION['user_id']) && $active_lesson['order_index'] == 1): ?>
                        <span class="badge" style="background: #fef08a; color: #854d0e; margin-left: 10px;">Free Preview</span>
                    <?php endif; ?>
                </h3>

                <?php
                // THE FREEMIUM LOGIC ENGINE
                $is_guest = !isset($_SESSION['user_id']);
                $is_preview = ($active_lesson['order_index'] == 1);

                if (!$is_guest || $is_preview):
                ?>
                    <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); background: #000; margin-bottom: 15px;">
                        <div id="video-transform-wrapper" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; transition: transform 0.3s ease;">
                            <div id="youtube-api-player" style="width: 100%; height: 100%;"></div>
                        </div>
                    </div>

                    <div style="display: flex; flex-wrap: wrap; gap: 10px; background: var(--bg-card); padding: 15px; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); margin-bottom: 30px; align-items: center;">
                        <span style="font-weight: bold; color: var(--primary); font-size: 0.9rem; margin-right: 10px;">PRO TOOLS:</span>
                        <button onclick="toggleMirror()" id="btn-mirror" class="btn btn-outline" style="padding: 8px 15px; font-size: 0.85rem;"><i class="fa-solid fa-right-left"></i> Mirror</button>
                        <div style="border-left: 2px solid var(--bg-main); height: 30px; margin: 0 5px;"></div>
                        <span style="font-weight: bold; color: var(--text-muted); font-size: 0.85rem;">SPEED:</span>
                        <button onclick="setSpeed(0.5)" class="btn btn-outline" style="padding: 8px 15px; font-size: 0.85rem;">0.5x</button>
                        <button onclick="setSpeed(0.75)" class="btn btn-outline" style="padding: 8px 15px; font-size: 0.85rem;">0.75x</button>
                        <button onclick="setSpeed(1.0)" class="btn btn-outline" style="padding: 8px 15px; font-size: 0.85rem; background: var(--bg-main);">1.0x</button>
                    </div>

                <?php else: ?>
                    <div style="background: var(--bg-card); padding: 60px 20px; text-align: center; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); margin-bottom: 30px; border: 2px solid var(--primary);">
                        <i class="fa-solid fa-lock" style="font-size: 3rem; color: var(--primary); margin-bottom: 20px;"></i>
                        <h2 style="margin-bottom: 15px;">Unlock the Full Syllabus</h2>
                        <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 25px; max-width: 400px; margin-left: auto; margin-right: auto;">
                            You have completed the free preview. Create a free account to track your progress and access the rest of this course.
                        </p>
                        <a href="register.php" class="btn btn-primary" style="font-size: 1.1rem; padding: 12px 30px;">Create Free Account</a>
                        <p style="margin-top: 15px; font-size: 0.9rem; color: var(--text-muted);">
                            Already have an account? <a href="login.php" style="color: var(--primary); font-weight: bold; text-decoration: none;">Log in</a>
                        </p>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div style="background: var(--bg-card); padding: 40px; text-align: center; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);">
                    <p style="color: var(--text-muted); font-size: 1.2rem;">The syllabus for this course is currently empty.</p>
                </div>
            <?php endif; ?>
        </div>

        <div style="background: var(--bg-card); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); padding: 25px; height: fit-content; align-self: start;">
            <h3 style="border-bottom: 2px solid var(--bg-main); padding-bottom: 15px; margin-bottom: 20px;">Course Syllabus</h3>

            <?php if (!empty($lessons)): ?>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <?php foreach ($lessons as $lesson): ?>
                        <?php
                        // UI Logic: Highlight the currently playing video
                        $is_active = ($active_lesson && $active_lesson['lesson_id'] == $lesson['lesson_id']);
                        $bg_color = $is_active ? 'var(--bg-main)' : 'transparent';
                        $border_color = $is_active ? 'var(--primary)' : '#e5e7eb';
                        ?>
                        <a href="lesson.php?class_id=<?php echo $class_id; ?>&lesson_id=<?php echo $lesson['lesson_id']; ?>"
                            style="text-decoration: none; color: var(--text-dark); display: flex; align-items: center; gap: 15px; padding: 12px; border: 1px solid <?php echo $border_color; ?>; border-radius: var(--radius-md); background: <?php echo $bg_color; ?>; transition: 0.2s;">

                            <div style="background: <?php echo $is_active ? 'var(--primary-gradient)' : '#f3f4f6'; ?>; color: <?php echo $is_active ? 'white' : 'var(--text-muted)'; ?>; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; font-size: 0.9rem; flex-shrink: 0;">
                                <?php echo htmlspecialchars($lesson['order_index']); ?>
                            </div>
                            <div style="font-weight: <?php echo $is_active ? '600' : '400'; ?>; line-height: 1.3;">
                                <?php echo htmlspecialchars($lesson['lesson_title']); ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="color: var(--text-muted); font-size: 0.9rem;">No lessons available yet.</p>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
    // 1. Load the YouTube IFrame Player API asynchronously
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    // 2. Initialize Player
    var player;

    function onYouTubeIframeAPIReady() {
        var videoId = '<?php echo $youtube_video_id; ?>';
        if (videoId) {
            player = new YT.Player('youtube-api-player', {
                videoId: videoId,
                playerVars: {
                    'playsinline': 1,
                    'rel': 0,
                    'modestbranding': 1
                }
            });
        }
    }

    // 3. Mirror Logic
    let isMirrored = false;

    function toggleMirror() {
        const wrapper = document.getElementById('video-transform-wrapper');
        const btn = document.getElementById('btn-mirror');

        if (!wrapper || !btn) return;

        isMirrored = !isMirrored;

        if (isMirrored) {
            wrapper.style.transform = 'scaleX(-1)';
            btn.style.background = 'var(--primary)';
            btn.style.color = 'white';
        } else {
            wrapper.style.transform = 'scaleX(1)';
            btn.style.background = 'transparent';
            btn.style.color = 'var(--primary)';
        }
    }

    // 4. Speed Control Logic
    function setSpeed(rate) {
        if (player && typeof player.setPlaybackRate === 'function') {
            player.setPlaybackRate(rate);
        }
    }
</script>

<?php include_once 'includes/footer.php'; ?>
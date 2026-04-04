<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container" style="max-width: 1200px; margin: 40px auto;">

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'purchase_success'): ?>
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0; text-align: center; font-weight: bold;">
            <i class="fa-solid fa-circle-check"></i> Payment successful! You now have full lifetime access to this course.
        </div>
    <?php endif; ?>

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

                <?php if ($canWatch): ?>
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

                    <?php
                    $is_completed = false;
                    $lessonModel = new \App\Models\Lesson();
                    if (isset($_SESSION['user_id'])) {
                        $is_completed = $lessonModel->isLessonCompleted($_SESSION['user_id'], $active_lesson['lesson_id']);
                    }
                    ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div style="background: var(--bg-card); padding: 20px; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); margin-bottom: 30px; border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h4 style="margin: 0 0 5px 0;">Track Your Progress</h4>
                                <p style="margin: 0; color: var(--text-muted); font-size: 0.9rem;">Watch the video to the very end to automatically log your progress.</p>
                            </div>

                            <?php if ($is_completed): ?>
                                <div id="completion-status" class="badge" style="background: #10b981; color: white; padding: 10px 15px; font-size: 1rem;"><i class="fa-solid fa-check"></i> Completed</div>
                            <?php else: ?>
                                <div id="completion-status" class="badge" style="background: #f59e0b; color: white; padding: 10px 15px; font-size: 1rem;"><i class="fa-solid fa-spinner fa-spin"></i> In Progress...</div>
                                <form style="display:none;"><?php echo \App\Core\Security::csrfField(); ?></form>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div style="background: var(--bg-card); padding: 60px 20px; text-align: center; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); margin-bottom: 30px; border: 2px solid var(--primary);">
                        <i class="fa-solid fa-lock" style="font-size: 3rem; color: var(--primary); margin-bottom: 20px;"></i>
                        <h2 style="margin-bottom: 15px;">Unlock the Full Syllabus</h2>
                        <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 25px; max-width: 400px; margin-left: auto; margin-right: auto;">
                            <?php if (!isset($_SESSION['user_id'])): ?>
                                Create a free account to access additional free lessons and track your progress.
                            <?php else: ?>
                                You have reached the end of the free lessons. Purchase this course to unlock lifetime access to all premium materials.
                            <?php endif; ?>
                        </p>

                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <a href="<?php echo BASE_URL; ?>/auth/register" class="btn btn-primary" style="font-size: 1.1rem; padding: 12px 30px;">Create Free Account</a>
                            <p style="margin-top: 15px; font-size: 0.9rem; color: var(--text-muted);">
                                Already have an account? <a href="<?php echo BASE_URL; ?>/auth/login" style="color: var(--primary); font-weight: bold; text-decoration: none;">Log in</a>
                            </p>
                        <?php else: ?>
                            <a href="<?php echo BASE_URL; ?>/checkout?class_id=<?php echo $course['class_id']; ?>" class="btn btn-primary" style="font-size: 1.1rem; padding: 12px 30px;"><i class="fa-solid fa-cart-shopping"></i> Purchase Full Course - $49.99</a>
                        <?php endif; ?>
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
                        $is_active = ($active_lesson && $active_lesson['lesson_id'] == $lesson['lesson_id']);
                        $bg_color = $is_active ? 'var(--bg-main)' : 'transparent';
                        $border_color = $is_active ? 'var(--primary)' : '#e5e7eb';

                        $is_locked_item = false;
                        if (!isset($_SESSION['user_id']) && $lesson['order_index'] > 1) {
                            $is_locked_item = true;
                        } elseif (isset($_SESSION['user_id']) && $lesson['order_index'] > 2 && empty($has_purchased)) {
                            $is_locked_item = true;
                        }

                        // FIXED: Removed the clone keyword. Directly querying the model.
                        $lesson_finished = false;
                        if (isset($_SESSION['user_id'])) {
                            $lesson_finished = $lessonModel->isLessonCompleted($_SESSION['user_id'], $lesson['lesson_id']);
                        }
                        ?>
                        <a href="<?php echo BASE_URL; ?>/lesson/view?class_id=<?php echo $course['class_id']; ?>&lesson_id=<?php echo $lesson['lesson_id']; ?>"
                            style="text-decoration: none; color: <?php echo $is_locked_item ? 'var(--text-muted)' : 'var(--text-dark)'; ?>; display: flex; align-items: center; gap: 15px; padding: 12px; border: 1px solid <?php echo $border_color; ?>; border-radius: var(--radius-md); background: <?php echo $bg_color; ?>; transition: 0.2s; <?php echo $is_locked_item ? 'opacity: 0.7;' : ''; ?>">

                            <div style="background: <?php echo $is_active ? 'var(--primary-gradient)' : '#f3f4f6'; ?>; color: <?php echo $is_active ? 'white' : 'var(--text-muted)'; ?>; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; font-size: 0.9rem; flex-shrink: 0;">
                                <?php if ($is_locked_item): ?>
                                    <i class="fa-solid fa-lock" style="font-size: 0.8rem;"></i>
                                <?php else: ?>
                                    <?php echo htmlspecialchars($lesson['order_index']); ?>
                                <?php endif; ?>
                            </div>

                            <div class="active-syllabus-item" style="font-weight: <?php echo $is_active ? '600' : '400'; ?>; line-height: 1.3; display: flex; align-items: center; justify-content: space-between; width: 100%;">
                                <span><?php echo htmlspecialchars($lesson['lesson_title']); ?></span>
                                <?php if ($lesson_finished): ?>
                                    <i class="fa-solid fa-circle-check" style="color: #10b981; margin-left: 10px;"></i>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div id="completion-status" class="badge" style="background: #f59e0b; color: white; padding: 10px 15px; font-size: 1rem;">
                        <i class="fa-solid fa-spinner fa-spin"></i> In Progress...
                    </div>
                    <button type="button" onclick="autoCompleteLesson()" style="background: transparent; border: 1px solid #ccc; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 0.8rem; color: #666;">
                        Mark Done
                    </button>
                </div>
                <form style="display:none;"><?php echo \App\Core\Security::csrfField(); ?></form>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php if ($active_lesson && $canWatch): ?>
    <script>
        window.LessonConfig = {
            videoId: '<?php echo htmlspecialchars($youtube_video_id ?? ''); ?>',
            classId: <?php echo intval($course['class_id'] ?? 0); ?>,
            lessonId: <?php echo intval($active_lesson['lesson_id'] ?? 0); ?>,
            baseUrl: '<?php echo BASE_URL; ?>'
        };
    </script>
    <script src="<?php echo BASE_URL; ?>/assets/js/lesson_tracking.js"></script>
<?php endif; ?>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
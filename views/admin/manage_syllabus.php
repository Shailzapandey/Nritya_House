<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container" style="max-width: 1000px; margin: 40px auto;">

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'lesson_added'): ?>
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
            <i class="fa-solid fa-circle-check"></i> Lesson added to the syllabus successfully.
        </div>
    <?php elseif (isset($_GET['msg']) && $_GET['msg'] == 'lesson_deleted'): ?>
        <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
            <i class="fa-solid fa-trash-can"></i> Lesson securely deleted.
        </div>
    <?php endif; ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h2 style="font-size: 2rem; color: var(--text-dark); margin-bottom: 5px;">Manage Syllabus</h2>
            <p style="color: var(--text-muted); font-size: 1.1rem;">Course: <strong><?php echo htmlspecialchars($course['title']); ?></strong></p>
        </div>
        <a href="<?php echo BASE_URL; ?>/admin/courses" class="btn btn-outline">Back to Catalog</a>
    </div>

    <div style="margin-bottom: 20px;">
        <a href="<?php echo BASE_URL; ?>/admin/addLesson?class_id=<?php echo $course['class_id']; ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add New Lesson</a>
    </div>

    <div style="background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                <th style="padding: 15px; width: 80px;">Order</th>
                <th style="padding: 15px;">Lesson Title</th>
                <th style="padding: 15px;">YouTube Link</th>
                <th style="padding: 15px; text-align: right;">Actions</th>
            </tr>

            <?php foreach ($lessons as $lesson): ?>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 15px;">
                        <span style="background: var(--bg-main); color: var(--primary); padding: 5px 10px; border-radius: 50%; font-weight: bold;">
                            <?php echo htmlspecialchars($lesson['order_index']); ?>
                        </span>
                    </td>
                    <td style="padding: 15px;"><strong><?php echo htmlspecialchars($lesson['lesson_title']); ?></strong></td>
                    <td style="padding: 15px; color: var(--text-muted); font-size: 0.9rem;">
                        <?php echo htmlspecialchars(substr($lesson['video_url'], 0, 40)) . '...'; ?>
                    </td>
                    <td style="padding: 15px; text-align: right;">
                        <form action="<?php echo BASE_URL; ?>/admin/deleteLesson" method="POST" onsubmit="return confirm('Delete this lesson?');" style="display: inline;">
                            <?php echo \App\Core\Security::csrfField(); ?>
                            <input type="hidden" name="lesson_id" value="<?php echo $lesson['lesson_id']; ?>">
                            <input type="hidden" name="class_id" value="<?php echo $course['class_id']; ?>">
                            <button type="submit" class="btn btn-outline" style="padding: 5px 10px; color: #ef4444; border-color: #ef4444;">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php if (empty($lessons)): ?>
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                <i class="fa-solid fa-film" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.5;"></i>
                <p>This syllabus is empty. Add a lesson to start building the course.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
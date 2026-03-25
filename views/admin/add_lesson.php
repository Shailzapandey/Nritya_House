<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container" style="max-width: 600px; margin: 40px auto; background: white; padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md);">
    <h2 style="border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 30px;">Add Video to Syllabus</h2>

    <form action="<?php echo BASE_URL; ?>/admin/processAddLesson" method="POST">
        <?php echo \App\Core\Security::csrfField(); ?>

        <input type="hidden" name="class_id" value="<?php echo $classId; ?>">

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Lesson Title *</label>
            <input type="text" name="lesson_title" required placeholder="e.g., Introduction to Footwork" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">YouTube Video URL *</label>
            <input type="url" name="video_url" required placeholder="https://www.youtube.com/watch?v=..." style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            <small style="color: #666;">Paste the full YouTube link here. Our system will automatically extract it for the player.</small>
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Lesson Order Number *</label>
            <input type="number" name="order_index" required min="1" placeholder="1, 2, 3..." style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            <small style="color: #666;">This determines the order the lesson appears in the syllabus list.</small>
        </div>

        <div style="display: flex; gap: 15px;">
            <button type="submit" class="btn btn-primary" style="flex: 1; padding: 12px; font-size: 1.1rem;">Save Lesson</button>
            <a href="<?php echo BASE_URL; ?>/admin/manageSyllabus?id=<?php echo $classId; ?>" class="btn btn-outline" style="padding: 12px; text-align: center;">Cancel</a>
        </div>
    </form>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
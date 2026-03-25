<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container" style="max-width: 800px; margin: 40px auto; background: white; padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 15px;">
        <h2 style="margin: 0;">Add New Course</h2>
        <a href="<?php echo BASE_URL; ?>/admin/courses" class="btn btn-outline">Back to Catalog</a>
    </div>

    <form action="<?php echo BASE_URL; ?>/admin/processAddCourse" method="POST" enctype="multipart/form-data">
        <?php echo \App\Core\Security::csrfField(); ?>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Course Title *</label>
            <input type="text" name="title" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Instructor Name *</label>
                <select name="instructor_id" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">Select an Instructor</option>
                    <?php foreach ($instructors as $inst): ?>
                        <option value="<?php echo $inst['instructor_id']; ?>"><?php echo htmlspecialchars($inst['full_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Dance Style *</label>
                <input type="text" name="style" required placeholder="e.g., Hip Hop, Ballet" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Difficulty Level *</label>
                <select name="difficulty_level" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Advanced">Advanced</option>
                </select>
            </div>
            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Duration (Minutes) *</label>
                <input type="number" name="duration_min" required min="1" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Course Thumbnail Image</label>
            <input type="file" name="thumbnail" accept="image/*" style="width: 100%; padding: 10px; border: 1px dashed #ccc; border-radius: 4px; background: #f9fafb;">
            <small style="color: #666;">Allowed formats: JPG, PNG, WEBP. Recommended size: 800x600px.</small>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.1rem; padding: 12px;">Create Course</button>
    </form>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
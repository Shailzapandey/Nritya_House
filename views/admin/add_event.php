<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container" style="max-width: 800px; margin: 40px auto; background: white; padding: 40px; border-radius: var(--radius-lg); box-shadow: var(--shadow-md);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 15px;">
        <h2 style="margin: 0;">Schedule Event</h2>
        <a href="<?php echo BASE_URL; ?>/admin/events" class="btn btn-outline">Cancel</a>
    </div>

    <form action="<?php echo BASE_URL; ?>/admin/processAddEvent" method="POST" enctype="multipart/form-data">
        <?php echo \App\Core\Security::csrfField(); ?>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Event Title *</label>
            <input type="text" name="title" required placeholder="e.g., Summer Showcase 2026" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Date & Time *</label>
                <input type="datetime-local" name="event_date" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div>
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Location *</label>
                <input type="text" name="location" required placeholder="e.g., Grand Theater" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Event Description *</label>
            <textarea name="description" required rows="4" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; resize: vertical;"></textarea>
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Event Image / Poster (Optional)</label>
            <input type="file" name="image" accept="image/*" style="width: 100%; padding: 10px; border: 1px dashed #ccc; border-radius: 4px; background: #f9fafb;">
            <small style="color: #666;">Will be displayed in the public timeline and gallery.</small>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.1rem; padding: 12px;">Publish Event</button>
    </form>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
<?php
// admin_classes.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

// THE STRICT BOUNCER: Kick out anyone who is NOT an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // In a real app, log this intrusion attempt. For now, kick them to index.
    header("Location: index.php");
    exit;
}

// Fetch all existing classes so the admin can see what's already in the catalog
try {
    $stmt = $pdo->query("SELECT * FROM classes ORDER BY class_id DESC");
    $all_classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

include_once 'includes/header.php';
?>

<div class="container" style="max-width: 1000px; margin: 0 auto;">
    <h2 style="margin-bottom: 20px;">Admin Control Panel: Manage Catalog</h2>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'added'): ?>
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            New class successfully added to the catalog!
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            Class successfully permanently deleted from the system.
        </div>
    <?php endif; ?>

    <div style="display: flex; gap: 30px; flex-wrap: wrap;">

        <div style="flex: 1; min-width: 300px; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); align-self: flex-start;">
            <h3 style="margin-bottom: 20px; border-bottom: 2px solid #f3f4f6; padding-bottom: 10px;">Add New Class</h3>

            <form action="actions/add_class.php" method="POST" enctype="multipart/form-data">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Class Title</label>
                    <input type="text" name="title" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Instructor Name</label>
                    <input type="text" name="instructor" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Dance Style</label>
                    <select name="style" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="Kathak">Kathak</option>
                        <option value="Bhangra">Bhangra</option>
                        <option value="Ghoomar">Ghoomar</option>
                        <option value="Garba">Garba</option>
                        <option value="Bollywood">Bollywood</option>
                    </select>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Difficulty Level</label>
                    <select name="difficulty_level" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="Beginner">Beginner</option>
                        <option value="Intermediate">Intermediate</option>
                        <option value="Advanced">Advanced</option>
                    </select>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Duration (Minutes)</label>
                    <input type="number" name="duration_min" required min="10" max="180" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Video Embed URL (YouTube/Vimeo)</label>
                    <input type="url" name="video_url" placeholder="e.g., https://www.youtube.com/embed/..." required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <small style="color: #666; font-size: 0.8rem;">Must be the embed URL, not the standard watch link.</small>
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Class Thumbnail Image</label>
                    <input type="file" name="thumbnail" accept="image/jpeg, image/png, image/webp" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; background: #f9fafb;">
                    <small style="color: #666; font-size: 0.8rem;">Max size: 2MB. Recommended ratio: 16:9.</small>
                </div>
                <button type="submit" style="width: 100%; padding: 10px; background: #ec4899; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                    Publish Class

                </button>
            </form>
        </div>

        <div style="flex: 2; min-width: 400px; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 20px; border-bottom: 2px solid #f3f4f6; padding-bottom: 10px;">Current Catalog</h3>

            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                        <th style="padding: 10px;">Title</th>
                        <th style="padding: 10px;">Style</th>
                        <th style="padding: 10px;">Level</th>
                        <th style="padding: 10px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_classes as $class): ?>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 10px;"><?php echo htmlspecialchars($class['title']); ?></td>
                            <td style="padding: 10px;"><?php echo htmlspecialchars($class['style']); ?></td>
                            <td style="padding: 10px;">
                                <span style="background: #e5e7eb; padding: 2px 6px; border-radius: 4px; font-size: 0.8rem;">
                                    <?php echo htmlspecialchars($class['difficulty_level']); ?>
                                </span>
                            </td>
                            <td style="padding: 10px;">
                                <form action="actions/delete_class.php" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to delete this class? This will instantly erase all student enrollments and progress for this course. This cannot be undone.');">
                                    <input type="hidden" name="class_id" value="<?php echo $class['class_id']; ?>">
                                    <button type="submit" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem; font-weight: bold;">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php include_once 'includes/footer.php'; ?>
<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container" style="max-width: 1200px; margin: 40px auto;">

    <?php if (isset($_GET['msg'])): ?>
        <?php if ($_GET['msg'] == 'course_added'): ?>
            <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
                <i class="fa-solid fa-circle-check"></i> Course successfully added to the catalog.
            </div>
        <?php elseif ($_GET['msg'] == 'course_updated'): ?>
            <div style="background: #e0f2fe; color: #1e3a8a; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
                <i class="fa-solid fa-pen-to-square"></i> Course details successfully updated.
            </div>
        <?php elseif ($_GET['msg'] == 'course_deleted'): ?>
            <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
                <i class="fa-solid fa-trash-can"></i> Course and all associated data have been permanently deleted.
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="font-size: 2rem; color: var(--text-dark);">Manage Course Catalog</h2>
        <a href="<?php echo BASE_URL; ?>/admin/users" class="btn btn-outline"><i class="fa-solid fa-users"></i> Manage Users</a>
        <a href="<?php echo BASE_URL; ?>/admin/events" class="btn btn-outline"><i class="fa-solid fa-calendar"></i> Manage Events</a>
    </div>

    <div style="margin-bottom: 20px;">
        <a href="<?php echo BASE_URL; ?>/admin/addCourse" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add New Course</a>
    </div>

    <div style="overflow-x: auto; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);">
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                <th style="padding: 15px;">ID</th>
                <th style="padding: 15px;">Course Title</th>
                <th style="padding: 15px;">Instructor</th>
                <th style="padding: 15px;">Level</th>
                <th style="padding: 15px;">Actions</th>
            </tr>

            <?php foreach ($classes as $course): ?>
                <tr style="border-bottom: 1px solid #e2e8f0; transition: background 0.2s;">
                    <td style="padding: 15px; color: var(--text-muted);"><?php echo $course['class_id']; ?></td>
                    <td style="padding: 15px;"><strong><?php echo htmlspecialchars($course['title']); ?></strong></td>

                    <td style="padding: 15px;"><i class="fa-solid fa-chalkboard-user" style="color: #9ca3af;"></i> <?php echo htmlspecialchars($course['instructor_name'] ?? 'Unassigned'); ?></td>

                    <td style="padding: 15px;">
                        <?php
                        $lvl_class = 'badge-beginner';
                        if ($course['difficulty_level'] == 'Intermediate') $lvl_class = 'badge-intermediate';
                        if ($course['difficulty_level'] == 'Advanced') $lvl_class = 'badge-advanced';
                        ?>
                        <span class="badge <?php echo $lvl_class; ?>"><?php echo htmlspecialchars($course['difficulty_level']); ?></span>
                    </td>

                    <td style="padding: 15px; display: flex; gap: 10px;">
                        <a href="<?php echo BASE_URL; ?>/admin/manageSyllabus?id=<?php echo $course['class_id']; ?>" class="btn btn-outline" style="padding: 5px 10px; color: #10b981; border-color: #10b981; text-decoration: none;">Syllabus</a>

                        <a href="<?php echo BASE_URL; ?>/admin/editCourse?id=<?php echo $course['class_id']; ?>" class="btn btn-outline" style="padding: 5px 10px; text-decoration: none;">Edit</a>

                        <form action="<?php echo BASE_URL; ?>/admin/deleteCourse" method="POST" onsubmit="return confirm('Are you absolutely sure you want to delete this course? This will also delete all associated lessons and enrollments.');">
                            <?php echo \App\Core\Security::csrfField(); ?>
                            <input type="hidden" name="class_id" value="<?php echo $course['class_id']; ?>">
                            <button type="submit" class="btn btn-outline" style="padding: 5px 10px; color: #ef4444; border-color: #ef4444; cursor: pointer;">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php if (empty($classes)): ?>
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                <i class="fa-solid fa-folder-open" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.5;"></i>
                <p>No courses found in the catalog. Add one to get started.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
<?php
// 1. Bring in the database connection
require_once 'config/database.php';

// 2. Bring in the top UI shell
include_once 'includes/header.php';

// 3. THE LOGIC (Backend)
// We write a SQL query to select everything from the classes table
$sql = "SELECT * FROM classes";

// We prepare and execute the query securely using our $pdo connection
$stmt = $pdo->prepare($sql);
$stmt->execute();

// We fetch all the rows and store them in a PHP array called $courses
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h2 style="margin-bottom: 30px;">Course Catalog</h2>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-top: 20px;">
        <?php foreach ($courses as $course): ?>
            <div class="card" style="background: white; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); display: flex; flex-direction: column; overflow: hidden;">

                <?php
                $thumb_path = !empty($course['thumbnail_url']) ? 'assets/images/' . $course['thumbnail_url'] : 'assets/images/default_thumb.jpg';
                ?>
                <img src="<?php echo htmlspecialchars($thumb_path); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" style="width: 100%; height: 200px; object-fit: cover; background: #e5e7eb;">

                <div style="padding: 20px; display: flex; flex-direction: column; flex-grow: 1;">

                    <span style="background: #a855f7; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; width: fit-content; margin-bottom: 10px;">
                        <?php echo htmlspecialchars($course['difficulty_level']); ?>
                    </span>

                    <h3 style="margin: 0 0 5px 0;"><?php echo htmlspecialchars($course['title']); ?></h3>
                    <p style="color: #666; font-size: 0.9rem; margin-bottom: 15px;">Instructor: <?php echo htmlspecialchars($course['instructor']); ?></p>

                    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; font-size: 0.85rem; color: #444;">
                        <span>Style: <?php echo htmlspecialchars($course['style']); ?></span>
                        <span><?php echo htmlspecialchars($course['duration_min']); ?> min</span>
                    </div>

                    <div style="margin-top: auto;">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <form action="actions/enroll.php" method="POST">
                                <input type="hidden" name="class_id" value="<?php echo $course['class_id']; ?>">
                                <button type="submit" style="width: 100%; padding: 10px; background: #a855f7; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                                    Enroll Now
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="login.php" style="display: block; text-align: center; width: 100%; padding: 10px; background: #e5e7eb; color: #4b5563; text-decoration: none; border-radius: 4px; font-weight: bold;">
                                Log in to Enroll
                            </a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>

    </div>
    <?php
    // 6. ERROR HANDLING
    // What if the database is empty? We tell the user.
    if (count($courses) === 0) {
        echo "<p>No classes are currently available.</p>";
    }
    ?>

</div>
</div>

<?php
// 7. Bring in the bottom UI shell
include_once 'includes/footer.php';
?>
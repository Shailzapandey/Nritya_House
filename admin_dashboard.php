<?php
// admin_dashboard.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

// STRICT BOUNCER
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

try {
    // Metric 1: Total Registered Students (excluding admins)
    $stmt_users = $pdo->query("SELECT COUNT(*) as total_students FROM users WHERE role = 'student'");
    $total_students = $stmt_users->fetch(PDO::FETCH_ASSOC)['total_students'];

    // Metric 2: Total Classes in Catalog
    $stmt_classes = $pdo->query("SELECT COUNT(*) as total_classes FROM classes");
    $total_classes = $stmt_classes->fetch(PDO::FETCH_ASSOC)['total_classes'];

    // Metric 3: Total Active Enrollments
    $stmt_enrollments = $pdo->query("SELECT COUNT(*) as total_enrollments FROM enrollments");
    $total_enrollments = $stmt_enrollments->fetch(PDO::FETCH_ASSOC)['total_enrollments'];

    // Metric 4: Popularity by Dance Style (The GROUP BY query)
    $stmt_styles = $pdo->query("
        SELECT c.style, COUNT(e.user_id) as style_count 
        FROM classes c 
        LEFT JOIN enrollments e ON c.class_id = e.class_id 
        GROUP BY c.style 
        ORDER BY style_count DESC
    ");
    $style_stats = $stmt_styles->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Analytics Error: " . $e->getMessage());
}

include_once 'includes/header.php';
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>System Analytics Overview</h2>
        <a href="admin_classes.php" style="padding: 8px 16px; background: #ec4899; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Manage Catalog</a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px;">

        <div style="background: white; padding: 25px; border-radius: 8px; border-top: 4px solid #3b82f6; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;">
            <h3 style="color: #6b7280; font-size: 1rem; margin-bottom: 10px;">Total Students</h3>
            <span style="font-size: 2.5rem; font-weight: bold; color: #1f2937;"><?php echo $total_students; ?></span>
        </div>

        <div style="background: white; padding: 25px; border-radius: 8px; border-top: 4px solid #a855f7; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;">
            <h3 style="color: #6b7280; font-size: 1rem; margin-bottom: 10px;">Active Classes</h3>
            <span style="font-size: 2.5rem; font-weight: bold; color: #1f2937;"><?php echo $total_classes; ?></span>
        </div>

        <div style="background: white; padding: 25px; border-radius: 8px; border-top: 4px solid #22c55e; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;">
            <h3 style="color: #6b7280; font-size: 1rem; margin-bottom: 10px;">Total Enrollments</h3>
            <span style="font-size: 2.5rem; font-weight: bold; color: #1f2937;"><?php echo $total_enrollments; ?></span>
        </div>

    </div>

    <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom: 20px; border-bottom: 2px solid #f3f4f6; padding-bottom: 10px;">Enrollments by Dance Style</h3>
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 12px;">Dance Style</th>
                    <th style="padding: 12px;">Total Enrollments</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($style_stats as $stat): ?>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px; font-weight: 500;"><?php echo htmlspecialchars($stat['style']); ?></td>
                        <td style="padding: 12px;">
                            <span style="background: #e5e7eb; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold;">
                                <?php echo $stat['style_count']; ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>
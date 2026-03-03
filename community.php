<?php
// community.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

// Guard Check: Kick out guests
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$current_user_id = $_SESSION['user_id'];

// --- 1. HANDLE POST SUBMISSION (CREATE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_content'])) {
    $content = trim($_POST['post_content']);

    if (!empty($content)) {
        try {
            $insert_stmt = $pdo->prepare("INSERT INTO community_posts (user_id, content) VALUES (?, ?)");
            $insert_stmt->execute([$current_user_id, $content]);

            // Redirect to prevent form resubmission on page refresh (Post/Redirect/Get pattern)
            header("Location: community.php");
            exit;
        } catch (PDOException $e) {
            die("Failed to post: " . $e->getMessage());
        }
    }
}

// --- 2. FETCH THE FEED (READ) ---
try {
    // The Master's Level JOIN Query
    // We pull the post data AND the author's name from the users table
    $sql = "SELECT cp.content, cp.created_at, full_name AS author_name 
            FROM community_posts cp
            INNER JOIN users u ON cp.user_id = u.user_id
            ORDER BY cp.created_at DESC"; // Newest posts first

    $stmt = $pdo->query($sql);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Failed to fetch community feed: " . $e->getMessage());
}

// --- 3. THE UI SHELL ---
include_once 'includes/header.php';
?>

<div class="container">
    <div style="margin-bottom: 30px; padding-bottom: 10px; border-bottom: 2px solid #eee;">
        <h2>Student Community Board</h2>
        <p style="color: #666;">Connect, ask questions, and share your progress.</p>
    </div>

    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 40px;">
        <form action="community.php" method="POST">
            <textarea name="post_content" rows="4" placeholder="What are you working on today?" style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 15px; font-family: inherit; resize: vertical;" required></textarea>
            <div style="text-align: right;">
                <button type="submit" style="background: var(--primary); color: white; padding: 10px 20px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                    Share Post <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>

    <div>
        <?php if (empty($posts)): ?>
            <div style="text-align: center; padding: 40px; color: #999;">
                <i class="fa-solid fa-comments" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
                <p>No posts yet. Be the first to start the conversation!</p>
            </div>
        <?php else: ?>

            <?php foreach ($posts as $post): ?>
                <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 20px; border-left: 4px solid var(--primary);">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <strong><i class="fa-solid fa-user-circle" style="color: #ccc; margin-right: 5px;"></i> <?php echo htmlspecialchars($post['author_name']); ?></strong>
                        <span style="color: #999; font-size: 0.85rem;">
                            <?php echo date('M j, Y - g:i A', strtotime($post['created_at'])); ?>
                        </span>
                    </div>
                    <div style="color: #333; line-height: 1.6;">
                        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
</div>

<?php
include_once 'includes/footer.php';
?>
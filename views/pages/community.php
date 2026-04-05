<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container" style="max-width: 800px; margin: 40px auto;">
    <div style="margin-bottom: 30px; padding-bottom: 10px; border-bottom: 2px solid #eee;">
        <h2 style="font-size: 2rem;">Student Community Board</h2>
        <p style="color: var(--text-muted);">Connect, ask questions, and share your progress.</p>
    </div>

    <div style="background: white; padding: 25px; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); margin-bottom: 40px;">
        <form action="<?php echo BASE_URL; ?>/community" method="POST">
            <?php echo \App\Core\Security::csrfField(); ?>
            <textarea name="post_content" rows="4" placeholder="What are you working on today?" style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: var(--radius-md); margin-bottom: 15px; font-family: inherit; resize: vertical;" required></textarea>
            <div style="text-align: right;">
                <button type="submit" class="btn btn-primary">Share Post <i class="fa-solid fa-paper-plane" style="margin-left: 5px;"></i></button>
            </div>
        </form>
    </div>

    <div style="display: flex; flex-direction: column; gap: 20px;">
        <?php if (empty($posts)): ?>
            <div style="text-align: center; padding: 40px; color: #999; background: white; border-radius: var(--radius-lg);">
                <i class="fa-solid fa-comments" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
                <p>No posts yet. Be the first to start the conversation!</p>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div style="background: white; padding: 25px; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); border-left: 4px solid var(--primary);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                        <div>
                            <strong style="font-size: 1.1rem;"><i class="fa-solid fa-user-circle" style="color: #ccc; margin-right: 5px;"></i> <?php echo htmlspecialchars($post['author_name']); ?></strong>
                            <div style="margin-top: 5px;">
                                <?php if ($post['role'] === 'admin'): ?>
                                    <span class="badge" style="background: #fee2e2; color: #991b1b; font-size: 0.7rem;">Admin</span>
                                <?php elseif ($post['role'] === 'instructor'): ?>
                                    <span class="badge" style="background: #e0e7ff; color: #3730a3; font-size: 0.7rem;">Instructor</span>
                                <?php else: ?>
                                    <span class="badge" style="background: #f1f5f9; color: #475569; font-size: 0.7rem;">Student</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <span style="color: #999; font-size: 0.85rem;">
                            <?php echo date('M j, Y - g:i A', strtotime($post['created_at'])); ?>
                        </span>
                    </div>
                    <div style="color: var(--text-dark); line-height: 1.6; font-size: 0.95rem;">
                        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
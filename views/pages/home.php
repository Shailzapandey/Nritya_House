<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container">
    <div class="hero-section">
        <div class="hero-content">
            <h1 class="text-gradient">Welcome to Nritya House</h1>
            <p>Learn dance from world-class instructors. Join live sessions or explore our extensive video library.</p>
            <div class="hero-buttons">
                <a href="<?php echo BASE_URL; ?>/classes" class="btn btn-primary">
                    <i class="fa-solid fa-play"></i> Start Learning
                </a>
                <a href="live_classes.php" class="btn btn-outline">
                    <i class="fa-regular fa-star"></i> Join Live Class
                </a>
            </div>
        </div>
        <div class="hero-image">
            <img src="assets/images/default_thumb.jpg" alt="Dancers performing">
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
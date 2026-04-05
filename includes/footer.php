</main>

<footer class="main-footer" style="background: #0f172a; color: #94a3b8; padding: 60px 0 30px; margin-top: 80px; border-top: 4px solid var(--primary);">
    <div class="container" style="display: flex; flex-wrap: wrap; gap: 40px; justify-content: space-between;">

        <div style="flex: 1; min-width: 250px;">
            <div style="color: white; font-size: 1.5rem; font-weight: bold; margin-bottom: 20px;">
                <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="Logo" style="height: 30px; vertical-align: middle; margin-right: 10px;"> Nritya House
            </div>
            <p style="line-height: 1.6; font-size: 0.95rem;">Empowering dancers worldwide through expert-led digital instruction and a vibrant community.</p>
        </div>

        <div style="display: flex; flex-wrap: wrap; gap: 60px; flex: 2; justify-content: flex-end; min-width: 300px;">
            <div>
                <h4 style="color: white; margin-bottom: 20px;">Platform</h4>
                <ul style="list-style: none; padding: 0; line-height: 2;">
                    <li><a href="<?php echo BASE_URL; ?>/course" style="color: inherit; text-decoration: none;">All Courses</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/instructor" style="color: inherit; text-decoration: none;">Instructors</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/event" style="color: inherit; text-decoration: none;">Events</a></li>
                </ul>
            </div>
            <div>
                <h4 style="color: white; margin-bottom: 20px;">Support</h4>
                <ul style="list-style: none; padding: 0; line-height: 2;">
                    <li><a href="<?php echo BASE_URL; ?>/community" style="color: inherit; text-decoration: none;">Community</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/live" style="color: inherit; text-decoration: none;">Live Classes</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/profile" style="color: inherit; text-decoration: none;">My Account</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 50px; padding-top: 20px; border-top: 1px solid #1e293b; text-align: center; font-size: 0.85rem;">
        <p>&copy; <?php echo date('Y'); ?> Nritya House. Master's Level MVC Project.</p>
    </div>
</footer>

<script src="<?php echo BASE_URL; ?>/assets/js/main.js?v=<?php echo time(); ?>"></script>

</body>

</html>
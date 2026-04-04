</main>
<footer style="background-color: #0f172a; color: #94a3b8; padding: 60px 0 20px; margin-top: auto; font-family: system-ui, sans-serif;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; border-bottom: 1px solid #1e293b; padding-bottom: 40px;">

            <div>
                <div class="logo" style="font-size: 1.5rem; font-weight: bold; color: white; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-fire-flame-curved" style="color: #ec4899;"></i> Nritya House
                </div>
                <p style="line-height: 1.6; font-size: 0.95rem;">Elevating the art of dance through world-class digital instruction and community engagement.</p>
            </div>

            <div>
                <h4 style="color: white; font-size: 1.1rem; margin-bottom: 20px;">Explore</h4>
                <ul style="list-style: none; padding: 0; line-height: 2.2;">
                    <li><a href="<?php echo BASE_URL; ?>/course" style="color: inherit; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#ec4899'" onmouseout="this.style.color='inherit'">Course Catalog</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/instructor" style="color: inherit; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#ec4899'" onmouseout="this.style.color='inherit'">Our Masters</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/event" style="color: inherit; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#ec4899'" onmouseout="this.style.color='inherit'">Upcoming Events</a></li>
                </ul>
            </div>

            <div>
                <h4 style="color: white; font-size: 1.1rem; margin-bottom: 20px;">Student Portal</h4>
                <ul style="list-style: none; padding: 0; line-height: 2.2;">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="<?php echo BASE_URL; ?>/dashboard" style="color: inherit; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#ec4899'" onmouseout="this.style.color='inherit'">My Dashboard</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/community" style="color: inherit; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#ec4899'" onmouseout="this.style.color='inherit'">Community Board</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/profile" style="color: inherit; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#ec4899'" onmouseout="this.style.color='inherit'">Profile Settings</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo BASE_URL; ?>/auth/login" style="color: inherit; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#ec4899'" onmouseout="this.style.color='inherit'">Student Login</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/auth/register" style="color: inherit; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#ec4899'" onmouseout="this.style.color='inherit'">Create Account</a></li>
                    <?php endif; ?>
                </ul>
            </div>

        </div>

        <div style="text-align: center; margin-top: 30px; font-size: 0.85rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 15px;">
            <div>&copy; <?php echo date("Y"); ?> Nritya House LMS. All rights reserved.</div>
            <div style="display: flex; gap: 20px;">
                <span style="opacity: 0.7;">Powered by Master's Level MVC Architecture</span>
            </div>
        </div>
    </div>
</footer>
</body>

</html>
<?php
require_once 'config/database.php';
include_once 'includes/header.php';

// If they are already logged in, redirect them away from the login page
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<div class="container" style="max-width: 400px; margin: 40px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <h2 style="text-align: center; margin-bottom: 20px;">Welcome Back</h2>
    <?php if (isset($_GET['error']) && $_GET['error'] == 'invalid_credentials'): ?>
        <div style="background: #fee2e2; color: #dc2626; padding: 10px; border-radius: 4px; margin-bottom: 15px; text-align: center; border: 1px solid #f87171;">
            Invalid email or password. Please try again.
        </div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>/auth/processLogin" method="POST">
        <?php echo \App\Core\Security::csrfField(); ?>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Email</label>
            <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Password</label>
            <input type="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <button type="submit" style="width: 100%; padding: 10px; background: #a855f7; color: white; border: none; border-radius: 4px; font-size: 1rem; cursor: pointer;">Log In</button>
    </form>

    <p style="text-align: center; margin-top: 15px; font-size: 0.9rem;">
        Don't have an account? <a href="<?php echo BASE_URL; ?>/auth/register" style="color: #a855f7; text-decoration: none;">Sign up</a>
    </p>
</div>

<?php include_once 'includes/footer.php'; ?>
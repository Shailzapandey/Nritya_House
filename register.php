<?php 
require_once 'config/database.php';
include_once 'includes/header.php'; 

// If the user is already logged in, they shouldn't be on the register page
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<div class="container" style="max-width: 400px; margin: 40px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <h2 style="text-align: center; margin-bottom: 20px;">Join Nritya House</h2>
    
    <form action="actions/auth_process.php" method="POST">
        
        <input type="hidden" name="action" value="register">

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Full Name</label>
            <input type="text" name="full_name" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Email</label>
            <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Password</label>
            <input type="password" name="password" required minlength="6" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <button type="submit" style="width: 100%; padding: 10px; background: #a855f7; color: white; border: none; border-radius: 4px; font-size: 1rem; cursor: pointer;">Create Account</button>
    </form>
    
    <p style="text-align: center; margin-top: 15px; font-size: 0.9rem;">
        Already have an account? <a href="login.php" style="color: #a855f7; text-decoration: none;">Log in</a>
    </p>
</div>

<?php include_once 'includes/footer.php'; ?>
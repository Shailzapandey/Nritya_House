<?php 
// 1. Include the Database connection (if you need it on the home page)
require_once 'config/database.php';

// 2. Include the Top Shell
include_once 'includes/header.php'; 
?>

<div class="container">
    <h1 style="font-size: 3rem; margin-bottom: 20px;">Welcome to <span style="color: var(--primary);">Nritya House</span></h1>
    <p>This is your dynamic modular setup. Notice how you didn't have to write the navigation bar or footer in this file.</p>
</div>
<?php 
// 3. Include the Bottom Shell
include_once 'includes/footer.php'; 
?>
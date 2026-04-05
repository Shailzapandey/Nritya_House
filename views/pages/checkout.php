<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container" style="max-width: 600px; margin: 50px auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
    <h2 style="border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">Secure Checkout</h2>

    <div style="margin-bottom: 30px;">
        <h3 style="color: var(--text-dark); margin-bottom: 5px;">Unlock Full Course: <?php echo htmlspecialchars($course['title']); ?></h3>
        <p style="color: var(--text-muted);">Get lifetime access to all lessons and materials.</p>
    </div>

    <form action="<?php echo BASE_URL; ?>/checkout?class_id=<?php echo $classId; ?>" method="POST" style="margin-bottom: 30px; background: #f9fafb; padding: 15px; border-radius: 6px;">
        <?php echo \App\Core\Security::csrfField(); ?>
        <label style="display: block; font-weight: bold; margin-bottom: 10px;">Have a Promo Code?</label>
        <div style="display: flex; gap: 10px;">
            <input type="text" name="coupon_code" placeholder="Enter code (e.g., DEFENSE100)" style="flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            <button type="submit" name="apply_coupon" class="btn btn-outline">Apply</button>
        </div>
        <div style="margin-top: 10px; font-weight: bold;"><?php echo $couponMessage; ?></div>
    </form>

    <div style="display: flex; justify-content: space-between; font-size: 1.2rem; margin-bottom: 10px;">
        <span>Base Price:</span>
        <span>Rs.<?php echo number_format($basePrice, 2); ?></span>
    </div>
    <div style="display: flex; justify-content: space-between; font-size: 1.2rem; color: #ec4899; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 20px;">
        <span>Discount:</span>
        <span>-Rs.<?php echo number_format($discount, 2); ?></span>
    </div>
    <div style="display: flex; justify-content: space-between; font-size: 1.5rem; font-weight: bold; margin-bottom: 30px;">
        <span>Total:</span>
        <span>Rs.<?php echo number_format($finalPrice, 2); ?></span>
    </div>

    <form action="<?php echo BASE_URL; ?>/checkout/process" method="POST">
        <?php echo \App\Core\Security::csrfField(); ?>
        <input type="hidden" name="class_id" value="<?php echo $classId; ?>">
        <input type="hidden" name="final_amount" value="<?php echo $finalPrice; ?>">

        <div style="background: #eef2ff; border: 1px solid #c7d2fe; padding: 15px; border-radius: 6px; margin-bottom: 20px; text-align: center; color: #4f46e5;">
            <i class="fa-solid fa-lock"></i> Mock Payment Gateway Active
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.2rem; padding: 15px;">
            Complete Purchase
        </button>
    </form>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>
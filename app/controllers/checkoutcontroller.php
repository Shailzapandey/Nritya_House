<?php

namespace App\Controllers;

use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Coupon;
use App\Core\Database;

class CheckoutController
{

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }

        $classId = intval($_GET['class_id'] ?? 0);
        if (!$classId) {
            header("Location: " . BASE_URL . "/course");
            exit;
        }

        $lessonModel = new Lesson();
        $course = $lessonModel->getCourseDetails($classId);

        // Base price of course (mocking a Rs.1500 price tag)
        $basePrice = 1500.99;
        $discount = 0;
        $couponMessage = '';

        // Handle Coupon Application (AJAX or Form Post)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_coupon'])) {
            \App\Core\Security::verifyCsrf();
            $couponModel = new Coupon();
            $percentOff = $couponModel->validateCode($_POST['coupon_code']);

            if ($percentOff !== false) {
                $discount = ($basePrice * $percentOff) / 100;
                $couponMessage = "<span style='color: green;'>Coupon applied! $percentOff% off.</span>";
            } else {
                $couponMessage = "<span style='color: red;'>Invalid or expired coupon.</span>";
            }
        }

        $finalPrice = max(0, $basePrice - $discount);

        require_once __DIR__ . '/../../views/pages/checkout.php';
    }

    public function process()
    {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/course");
            exit;
        }

        \App\Core\Security::verifyCsrf();

        $userId = $_SESSION['user_id'];
        $classId = intval($_POST['class_id']);
        $finalAmount = floatval($_POST['final_amount']);

        $db = Database::getInstance()->getConnection();
        $paymentModel = new Payment();

        try {
            // THE TRANSACTIONAL LOCK
            $db->beginTransaction();

            // 1. Check if they already bought it to prevent duplicate charges
            if ($paymentModel->hasPurchased($userId, $classId)) {
                $db->rollBack();
                header("Location: " . BASE_URL . "/lesson/view?class_id=" . $classId);
                exit;
            }

            // 2. Process the dummy payment (In reality, Stripe API call goes here)
            $paymentSuccess = true; // Assume dummy gateway succeeded

            if (!$paymentSuccess) {
                throw new \Exception("Payment Gateway Declined.");
            }

            // 3. Record the secure purchase
            $paymentModel->recordPurchase($db, $userId, $classId, $finalAmount);

            // Commit the transaction - data is now permanent
            $db->commit();

            header("Location: " . BASE_URL . "/lesson/view?class_id=" . $classId . "&msg=purchase_success");
            exit;
        } catch (\Exception $e) {
            $db->rollBack(); // Abort everything if anything failed
            die("Transaction Failed: " . $e->getMessage());
        }
    }
}

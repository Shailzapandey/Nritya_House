<?php

namespace App\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Core\Database;

class CourseController
{

    // Display the main catalog
    public function index()
    {
        $courseModel = new Course();
        $courses = [];

        if (isset($_SESSION['user_id'])) {
            // Logged In: Run the Recommendation Engine
            $userId = $_SESSION['user_id'];

            // Quick DB pull to get user level (You can move this to User model later)
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT experience_level FROM users WHERE user_id = ?");
            $stmt->execute([$userId]);
            $userLevel = $stmt->fetchColumn();

            $courses = $courseModel->getAllWithRecommendations($userLevel);
        } else {
            // Guest User
            $courses = $courseModel->getAll();
        }

        // Hand the data off to the view
        require_once __DIR__ . '/../../views/pages/catalog.php';
    }
    // Route: /course/show?id=X
    public function show()
    {
        $id = intval($_GET['id']);
        $courseModel = new \App\Models\Course();
        $course = $courseModel->getById($id); // Ensure this method exists in your Model

        if (!$course) {
            header("Location: " . BASE_URL . "/course");
            exit;
        }

        $has_purchased = false;
        if (isset($_SESSION['user_id'])) {
            $paymentModel = new \App\Models\Payment();
            $has_purchased = $paymentModel->hasPurchased($_SESSION['user_id'], $id);
        }

        require_once __DIR__ . '/../../views/pages/course_detail.php';
    }
}

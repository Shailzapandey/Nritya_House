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
}

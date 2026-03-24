<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Enrollment;
use App\Services\GamificationService;

class DashboardController
{
    public function index()
    {
        // 1. Guard Clause: Kick out guests
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $userName = $_SESSION['user_name'];
        $role = $_SESSION['role'];

        // 2. The Assessment Choke-point
        if ($role !== 'admin') {
            $userModel = new User();
            // Assuming we added a getLevel method or just fetch the user
            $user = $userModel->findByEmail($_SESSION['email'] ?? ''); // Wait, email isn't in session. Let's query by ID instead.

            // Let's use raw DB just for this quick check to avoid rewriting User model right now
            $db = \App\Core\Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT experience_level FROM users WHERE user_id = ?");
            $stmt->execute([$userId]);
            $level = $stmt->fetchColumn();

            if (empty($level)) {
                header("Location: " . BASE_URL . "/assessment"); // We will route this later
                exit;
            }
        }

        // 3. Fetch Data
        $enrollmentModel = new Enrollment();
        $enrolled_classes = $enrollmentModel->getUserEnrollments($userId);

        $gamification = new GamificationService();
        $current_streak = $gamification->calculateCurrentStreak($userId);

        // 4. Render View
        require_once __DIR__ . '/../../views/pages/dashboard.php';
    }
}

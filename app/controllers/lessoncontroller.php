<?php

namespace App\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\Payment;

class LessonController
{
    // Route: /lesson/view
    public function view()
    {
        if (!isset($_GET['class_id'])) {
            header("Location: " . BASE_URL . "/course");
            exit;
        }

        $classId = intval($_GET['class_id']);
        $lessonId = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : null;

        $lessonModel = new Lesson();
        $course = $lessonModel->getCourseDetails($classId);

        if (!$course) {
            header("Location: " . BASE_URL . "/course");
            exit;
        }

        $lessons = $lessonModel->getSyllabus($classId);

        $active_lesson = null;
        if ($lessonId) {
            foreach ($lessons as $lesson) {
                if ($lesson['lesson_id'] == $lessonId) {
                    $active_lesson = $lesson;
                    break;
                }
            }
        } else {
            $active_lesson = !empty($lessons) ? $lessons[0] : null;
        }

        $youtube_video_id = '';
        if ($active_lesson) {
            // ROBUST YOUTUBE EXTRACTOR: Handles watch?v=, youtu.be/, embed/, and shorts/
            $url = $active_lesson['video_url'];
            $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
            if (preg_match($pattern, $url, $match)) {
                $youtube_video_id = $match[1];
            }
        }

        // --- ACCESS CONTROL LOGIC ---
        $is_guest = !isset($_SESSION['user_id']);
        $lesson_order = $active_lesson ? $active_lesson['order_index'] : 1;

        $has_purchased = false;
        if (!$is_guest) {
            $paymentModel = new Payment();
            $has_purchased = $paymentModel->hasPurchased($_SESSION['user_id'], $classId);
        }

        if ($is_guest) {
            $canWatch = ($lesson_order == 1);
        } else {
            if ($lesson_order <= 2) {
                $canWatch = true;
            } else {
                $canWatch = $has_purchased;
            }
        }

        require_once __DIR__ . '/../../views/pages/lesson_viewer.php';
    }

    // Route: /lesson/complete
    public function complete()
    {
        // Detect if this is an invisible background request
        $isAjax = isset($_POST['is_ajax']) && $_POST['is_ajax'] == '1';

        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit;
            }
            header("Location: " . BASE_URL . "/course");
            exit;
        }

        \App\Core\Security::verifyCsrf();

        $lessonId = intval($_POST['lesson_id']);
        $classId = intval($_POST['class_id']);
        $userId = $_SESSION['user_id'];

        $lessonModel = new Lesson();

        // 1. Mark specific lesson as complete
        $lessonModel->markComplete($userId, $lessonId);

        // 2. Calculate true mathematical progress and update ledger
        $progressPercent = $lessonModel->calculateCourseProgress($userId, $classId);
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE enrollments SET progress_percent = ? WHERE user_id = ? AND class_id = ?");
        $stmt->execute([$progressPercent, $userId, $classId]);

        // 3. Fire Gamification Service to extend daily streak
        // This will no longer crash because we added logDailyActivity() to the service!54
        require_once __DIR__ . '/../Services/GamificationService.php';
        $gamification = new \App\Services\GamificationService();
        $gamification->logDailyActivity($userId);

        // 4. Return JSON payload directly to the frontend script
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'progress' => $progressPercent]);
            exit;
        }

        // Fallback for non-ajax
        header("Location: " . BASE_URL . "/lesson/view?class_id=" . $classId . "&lesson_id=" . $lessonId . "&msg=completed");
        exit;
    }
}

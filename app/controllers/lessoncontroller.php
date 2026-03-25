<?php

namespace App\Controllers;

use App\Models\Lesson;

class LessonController
{

    public function view()
    {
        if (!isset($_GET['class_id'])) {
            header("Location: " . BASE_URL . "/course");
            exit;
        }

        $classId = intval($_GET['class_id']);
        $lessonModel = new Lesson();

        $course = $lessonModel->getCourseDetails($classId);
        if (!$course) {
            header("Location: " . BASE_URL . "/course");
            exit;
        }

        $lessons = $lessonModel->getSyllabus($classId);

        // Determine Active Lesson
        $active_lesson = null;
        if (!empty($lessons)) {
            if (isset($_GET['lesson_id'])) {
                $lessonId = intval($_GET['lesson_id']);
                foreach ($lessons as $lesson) {
                    if ($lesson['lesson_id'] == $lessonId) {
                        $active_lesson = $lesson;
                        break;
                    }
                }
            }
            if (!$active_lesson) {
                $active_lesson = $lessons[0];
            }
        }

        // --- MASTER ACCESS CONTROL LOGIC ---
        $is_guest = !isset($_SESSION['user_id']);
        $lesson_order = $active_lesson ? $active_lesson['order_index'] : 1;

        $has_purchased = false;
        if (!$is_guest) {
            $paymentModel = new \App\Models\Payment();
            $has_purchased = $paymentModel->hasPurchased($_SESSION['user_id'], $classId);
        }

        // Rule 1: Guests only see video 1.
        // Rule 2: Logged in users get videos 1 & 2 for free.
        // Rule 3: Video 3 and beyond require a purchase record.
        if ($is_guest) {
            $canWatch = ($lesson_order == 1);
        } else {
            if ($lesson_order <= 2) {
                $canWatch = true;
            } else {
                $canWatch = $has_purchased;
            }
        }

        // YouTube ID Extractor
        $youtube_video_id = '';
        if ($active_lesson && !empty($active_lesson['video_url'])) {
            $url = $active_lesson['video_url'];
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $url, $match);
            $youtube_video_id = $match[1] ?? end(explode('/', rtrim($url, '/')));
        }

        // Pass clean data to the View
        require_once __DIR__ . '/../../views/pages/lesson_viewer.php';
    }
}

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

        // Access Control Logic (Freemium & Future Paywall)
        $is_guest = !isset($_SESSION['user_id']);
        $is_preview = ($active_lesson && $active_lesson['order_index'] == 1);
        $canWatch = (!$is_guest || $is_preview);

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

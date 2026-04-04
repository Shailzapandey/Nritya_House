<?php

namespace App\Controllers;

use App\Core\Database;
use PDO;

class AdminController
{
    private $db;

    public function __construct()
    {
        // THE BOUNCER: Kick out guests and standard users instantly
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: " . BASE_URL . "/dashboard");
            exit;
        }
        $this->db = Database::getInstance()->getConnection();
    }

    // Default route redirects to courses
    public function index()
    {
        header("Location: " . BASE_URL . "/admin/courses");
        exit;
    }

    // --- DASHBOARD ROUTING ---

    // Route: /admin/courses
    public function courses()
    {
        // Relational JOIN to get the instructor's name instead of just the ID
        $sql = "SELECT c.*, i.full_name as instructor_name 
                FROM classes c 
                LEFT JOIN instructors i ON c.instructor_id = i.instructor_id 
                ORDER BY c.class_id DESC";
        $stmt = $this->db->query($sql);
        $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once __DIR__ . '/../../views/admin/manage_courses.php';
    }

    // Route: /admin/users
    public function users()
    {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once __DIR__ . '/../../views/admin/manage_users.php';
    }


    // --- COURSE CRUD OPERATIONS ---

    // Route: /admin/addCourse
    public function addCourse()
    {
        // Fetch instructors for the dropdown menu
        $instructorModel = new \App\Models\Instructor();
        $instructors = $instructorModel->getAll();

        require_once __DIR__ . '/../../views/admin/add_course.php';
    }

    // Route: /admin/processAddCourse
    public function processAddCourse()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/admin/courses");
            exit;
        }

        \App\Core\Security::verifyCsrf();

        $title = trim(htmlspecialchars($_POST['title']));
        $instructorId = intval($_POST['instructor_id']); // Relational ID
        $difficulty = trim(htmlspecialchars($_POST['difficulty_level']));
        $style = trim(htmlspecialchars($_POST['style']));
        $duration = intval($_POST['duration_min']);

        $thumbUrl = '';
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $tmpPath = $_FILES['thumbnail']['tmp_name'];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $tmpPath);
            finfo_close($finfo);

            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($mimeType, $allowedTypes)) {
                die("Security Error: Invalid image format. Only JPG, PNG, and WEBP are allowed.");
            }

            $extension = explode('/', $mimeType)[1];
            $fileName = 'thumb_' . uniqid() . '.' . $extension;
            $destination = __DIR__ . '/../../assets/images/' . $fileName;

            if (move_uploaded_file($tmpPath, $destination)) {
                $thumbUrl = $fileName;
            } else {
                die("System Error: Failed to save image.");
            }
        }

        $courseModel = new \App\Models\Course();
        if ($courseModel->create($title, $instructorId, $difficulty, $style, $duration, $thumbUrl)) {
            $notifModel = new \App\Models\Notification();
            $notifModel->broadcast("New Course Dropped: " . $title . "!", "/course");
            header("Location: " . BASE_URL . "/admin/courses?msg=course_added");
            exit;
        } else {
            die("Database Error: Could not create course.");
        }
    }

    // Route: /admin/editCourse
    public function editCourse()
    {
        if (!isset($_GET['id'])) {
            header("Location: " . BASE_URL . "/admin/courses");
            exit;
        }

        $classId = intval($_GET['id']);
        $courseModel = new \App\Models\Course();
        $course = $courseModel->findById($classId);

        if (!$course) {
            header("Location: " . BASE_URL . "/admin/courses");
            exit;
        }

        // Fetch instructors for the dropdown menu
        $instructorModel = new \App\Models\Instructor();
        $instructors = $instructorModel->getAll();

        require_once __DIR__ . '/../../views/admin/edit_course.php';
    }

    // Route: /admin/processEditCourse
    public function processEditCourse()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/admin/courses");
            exit;
        }

        \App\Core\Security::verifyCsrf();

        $classId = intval($_POST['class_id']);
        $title = trim(htmlspecialchars($_POST['title']));
        $instructorId = intval($_POST['instructor_id']); // Relational ID
        $difficulty = trim(htmlspecialchars($_POST['difficulty_level']));
        $style = trim(htmlspecialchars($_POST['style']));
        $duration = intval($_POST['duration_min']);

        $thumbUrl = null;

        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $tmpPath = $_FILES['thumbnail']['tmp_name'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $tmpPath);
            finfo_close($finfo);

            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($mimeType, $allowedTypes)) {
                die("Security Error: Invalid image format.");
            }

            $extension = explode('/', $mimeType)[1];
            $fileName = 'thumb_' . uniqid() . '.' . $extension;
            $destination = __DIR__ . '/../../assets/images/' . $fileName;

            if (move_uploaded_file($tmpPath, $destination)) {
                $thumbUrl = $fileName;
            }
        }

        $courseModel = new \App\Models\Course();
        if ($courseModel->update($classId, $title, $instructorId, $difficulty, $style, $duration, $thumbUrl)) {
            header("Location: " . BASE_URL . "/admin/courses?msg=course_updated");
            exit;
        } else {
            die("Database Error: Could not update course.");
        }
    }

    // Route: /admin/deleteCourse
    public function deleteCourse()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/admin/courses");
            exit;
        }

        \App\Core\Security::verifyCsrf();
        $classId = intval($_POST['class_id']);

        $courseModel = new \App\Models\Course();
        $courseModel->delete($classId);

        header("Location: " . BASE_URL . "/admin/courses?msg=course_deleted");
        exit;
    }


    // --- SYLLABUS MANAGEMENT METHODS ---

    // Route: /admin/manageSyllabus
    public function manageSyllabus()
    {
        if (!isset($_GET['id'])) {
            header("Location: " . BASE_URL . "/admin/courses");
            exit;
        }
        $classId = intval($_GET['id']);

        $lessonModel = new \App\Models\Lesson();
        $course = $lessonModel->getCourseDetails($classId);

        if (!$course) {
            header("Location: " . BASE_URL . "/admin/courses");
            exit;
        }

        $lessons = $lessonModel->getSyllabus($classId);
        require_once __DIR__ . '/../../views/admin/manage_syllabus.php';
    }

    // Route: /admin/addLesson
    public function addLesson()
    {
        if (!isset($_GET['class_id'])) {
            header("Location: " . BASE_URL . "/admin/courses");
            exit;
        }
        $classId = intval($_GET['class_id']);
        require_once __DIR__ . '/../../views/admin/add_lesson.php';
    }

    // Route: /admin/processAddLesson
    public function processAddLesson()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/admin/courses");
            exit;
        }

        \App\Core\Security::verifyCsrf();

        $classId = intval($_POST['class_id']);
        $title = trim(htmlspecialchars($_POST['lesson_title']));
        $videoUrl = trim(filter_var($_POST['video_url'], FILTER_SANITIZE_URL));
        $orderIndex = intval($_POST['order_index']);

        $lessonModel = new \App\Models\Lesson();
        if ($lessonModel->create($classId, $title, $videoUrl, $orderIndex)) {
            header("Location: " . BASE_URL . "/admin/manageSyllabus?id=" . $classId . "&msg=lesson_added");
            exit;
        } else {
            die("Database Error: Could not add lesson.");
        }
    }

    // Route: /admin/deleteLesson
    public function deleteLesson()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/admin/courses");
            exit;
        }

        \App\Core\Security::verifyCsrf();
        $lessonId = intval($_POST['lesson_id']);
        $classId = intval($_POST['class_id']);

        $lessonModel = new \App\Models\Lesson();
        $lessonModel->delete($lessonId);

        header("Location: " . BASE_URL . "/admin/manageSyllabus?id=" . $classId . "&msg=lesson_deleted");
        exit;
    }
    // Route: /admin/toggleUserRole
    public function toggleUserRole()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/admin/users");
            exit;
        }

        \App\Core\Security::verifyCsrf();

        $target_user_id = $_POST['target_user_id'] ?? '';
        $current_role = $_POST['current_role'] ?? '';
        $acting_admin_id = $_SESSION['user_id'];

        // The Self-Lockout Safeguard
        if ($target_user_id == $acting_admin_id && $current_role === 'admin') {
            header("Location: " . BASE_URL . "/admin/users?error=self_demotion");
            exit;
        }

        $new_role = ($current_role === 'admin') ? 'student' : 'admin';

        $stmt = $this->db->prepare("UPDATE users SET role = ? WHERE user_id = ?");
        $stmt->execute([$new_role, $target_user_id]);

        header("Location: " . BASE_URL . "/admin/users?msg=role_updated");
        exit;
    }
    // --- EVENT MANAGEMENT METHODS ---

    // Route: /admin/events
    public function events()
    {
        $eventModel = new \App\Models\Event();
        $events = $eventModel->getAll(); // Fetches both past and future
        require_once __DIR__ . '/../../views/admin/manage_events.php';
    }

    // Route: /admin/addEvent
    public function addEvent()
    {
        require_once __DIR__ . '/../../views/admin/add_event.php';
    }

    // Route: /admin/processAddEvent
    public function processAddEvent()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/admin/events");
            exit;
        }

        \App\Core\Security::verifyCsrf();

        $title = trim(htmlspecialchars($_POST['title']));
        $description = trim(htmlspecialchars($_POST['description']));
        $location = trim(htmlspecialchars($_POST['location']));

        // Ensure date is formatted correctly for MySQL DATETIME
        $eventDate = date('Y-m-d H:i:s', strtotime($_POST['event_date']));

        $imageUrl = 'default_thumb.jpg';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmpPath = $_FILES['image']['tmp_name'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $tmpPath);
            finfo_close($finfo);

            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($mimeType, $allowedTypes)) {
                die("Security Error: Invalid image format.");
            }

            $extension = explode('/', $mimeType)[1];
            $fileName = 'event_' . uniqid() . '.' . $extension;
            $destination = __DIR__ . '/../../assets/images/' . $fileName;

            if (move_uploaded_file($tmpPath, $destination)) {
                $imageUrl = $fileName;
            }
        }

        $eventModel = new \App\Models\Event();
        if ($eventModel->create($title, $description, $eventDate, $location, $imageUrl)) {
            $notifModel = new \App\Models\Notification();
            $notifModel->broadcast("New Event Scheduled: " . $title, "/event");
            header("Location: " . BASE_URL . "/admin/events?msg=event_added");
            exit;
        } else {
            die("Database Error: Could not create event.");
        }
    }

    // Route: /admin/deleteEvent
    public function deleteEvent()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/admin/events");
            exit;
        }

        \App\Core\Security::verifyCsrf();
        $eventId = intval($_POST['event_id']);

        $eventModel = new \App\Models\Event();
        $eventModel->delete($eventId);

        header("Location: " . BASE_URL . "/admin/events?msg=event_deleted");
        exit;
    }
}

<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Lesson
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getCourseDetails($classId)
    {
        $stmt = $this->db->prepare("SELECT class_id, title FROM classes WHERE class_id = ?");
        $stmt->execute([$classId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSyllabus($classId)
    {
        $stmt = $this->db->prepare("SELECT * FROM lessons WHERE class_id = ? ORDER BY order_index ASC");
        $stmt->execute([$classId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Securely insert a new lesson into the syllabus
    public function create($classId, $title, $videoUrl, $orderIndex)
    {
        $sql = "INSERT INTO lessons (class_id, lesson_title, video_url, order_index) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$classId, $title, $videoUrl, $orderIndex]);
    }

    // Delete a lesson from the syllabus
    public function delete($lessonId)
    {
        $sql = "DELETE FROM lessons WHERE lesson_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$lessonId]);
    }

    // --- NEW RELATIONAL PROGRESS METHODS ---

    public function isLessonCompleted($userId, $lessonId)
    {
        $stmt = $this->db->prepare("SELECT 1 FROM user_lesson_progress WHERE user_id = ? AND lesson_id = ?");
        $stmt->execute([$userId, $lessonId]);
        return (bool)$stmt->fetchColumn();
    }

    public function markComplete($userId, $lessonId)
    {
        $stmt = $this->db->prepare("INSERT IGNORE INTO user_lesson_progress (user_id, lesson_id) VALUES (?, ?)");
        return $stmt->execute([$userId, $lessonId]);
    }

    public function calculateCourseProgress($userId, $classId)
    {
        // 1. Get total lessons in the syllabus
        $stmt1 = $this->db->prepare("SELECT COUNT(*) FROM lessons WHERE class_id = ?");
        $stmt1->execute([$classId]);
        $totalLessons = $stmt1->fetchColumn();

        if ($totalLessons == 0) return 0;

        // 2. Get how many of those lessons the user has completed
        $stmt2 = $this->db->prepare("
            SELECT COUNT(ulp.lesson_id) 
            FROM user_lesson_progress ulp
            INNER JOIN lessons l ON ulp.lesson_id = l.lesson_id
            WHERE ulp.user_id = ? AND l.class_id = ?
        ");
        $stmt2->execute([$userId, $classId]);
        $completedLessons = $stmt2->fetchColumn();

        // 3. Mathematical precision
        return round(($completedLessons / $totalLessons) * 100);
    }
}

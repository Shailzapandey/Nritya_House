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
}

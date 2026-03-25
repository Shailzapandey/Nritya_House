<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Instructor
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM instructors ORDER BY full_name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM instructors WHERE instructor_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all courses taught by a specific instructor
    public function getCoursesByInstructor($instructorId)
    {
        $stmt = $this->db->prepare("SELECT * FROM classes WHERE instructor_id = ? ORDER BY class_id DESC");
        $stmt->execute([$instructorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

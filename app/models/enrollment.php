<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Enrollment
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getUserEnrollments($userId)
    {
        $sql = "SELECT c.*, i.full_name as instructor_name 
                FROM classes c 
                INNER JOIN enrollments e ON c.class_id = e.class_id 
                LEFT JOIN instructors i ON c.instructor_id = i.instructor_id
                WHERE e.user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

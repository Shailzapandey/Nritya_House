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
        $sql = "SELECT c.* FROM classes c 
                INNER JOIN enrollments e ON c.class_id = e.class_id 
                WHERE e.user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

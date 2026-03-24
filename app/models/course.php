<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Course
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // For Guest Users: Standard chronological catalog
    public function getAll()
    {
        $sql = "SELECT *, 0 AS is_recommended FROM classes ORDER BY class_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // For Logged-In Users: Recommendation Engine based on their level
    public function getAllWithRecommendations($userLevel)
    {
        $sql = "SELECT *, (difficulty_level = ?) AS is_recommended 
                FROM classes 
                ORDER BY is_recommended DESC, class_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userLevel]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

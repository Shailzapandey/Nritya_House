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

    public function getAll()
    {
        $sql = "SELECT c.*, i.full_name as instructor_name, 0 AS is_recommended 
                FROM classes c
                LEFT JOIN instructors i ON c.instructor_id = i.instructor_id 
                ORDER BY c.class_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllWithRecommendations($userLevel)
    {
        $sql = "SELECT c.*, i.full_name as instructor_name, (c.difficulty_level = ?) AS is_recommended 
                FROM classes c
                LEFT JOIN instructors i ON c.instructor_id = i.instructor_id 
                ORDER BY is_recommended DESC, c.class_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userLevel]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($classId)
    {
        $sql = "SELECT c.*, i.full_name as instructor_name 
                FROM classes c
                LEFT JOIN instructors i ON c.instructor_id = i.instructor_id 
                WHERE c.class_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$classId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($title, $instructorId, $difficulty, $style, $duration, $thumbUrl)
    {
        $sql = "INSERT INTO classes (title, instructor_id, difficulty_level, style, duration_min, thumbnail_url) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$title, $instructorId, $difficulty, $style, $duration, $thumbUrl]);
    }

    public function update($classId, $title, $instructorId, $difficulty, $style, $duration, $thumbUrl = null)
    {
        if ($thumbUrl) {
            $sql = "UPDATE classes SET title = ?, instructor_id = ?, difficulty_level = ?, style = ?, duration_min = ?, thumbnail_url = ? WHERE class_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$title, $instructorId, $difficulty, $style, $duration, $thumbUrl, $classId]);
        } else {
            $sql = "UPDATE classes SET title = ?, instructor_id = ?, difficulty_level = ?, style = ?, duration_min = ? WHERE class_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$title, $instructorId, $difficulty, $style, $duration, $classId]);
        }
    }

    public function delete($classId)
    {
        $sql = "DELETE FROM classes WHERE class_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$classId]);
    }
}

<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Find a user by their email address
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new user securely
    public function create($fullName, $email, $passwordHash)
    {
        $stmt = $this->db->prepare("INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)");
        return $stmt->execute([$fullName, $email, $passwordHash]);
    }
}

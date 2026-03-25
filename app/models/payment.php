<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Payment
{
    private $db;
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function hasPurchased($userId, $classId)
    {
        $stmt = $this->db->prepare("SELECT 1 FROM user_purchases WHERE user_id = ? AND class_id = ?");
        $stmt->execute([$userId, $classId]);
        return (bool) $stmt->fetchColumn();
    }

    // Notice we pass the raw PDO object so the Controller can manage the transaction
    public function recordPurchase($pdo, $userId, $classId, $amount)
    {
        $stmt = $pdo->prepare("INSERT INTO user_purchases (user_id, class_id, amount_paid) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $classId, $amount]);
    }
}

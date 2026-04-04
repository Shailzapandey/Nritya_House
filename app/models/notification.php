<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Notification
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Get unread alerts for a specific user
    public function getUnread($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Securely mark a specific alert as read
    public function markAsRead($notificationId, $userId)
    {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE notification_id = ? AND user_id = ?");
        return $stmt->execute([$notificationId, $userId]);
    }

    // MASS BROADCAST ENGINE: Target all non-admin students
    public function broadcast($message, $link = '')
    {
        $stmt = $this->db->query("SELECT user_id FROM users WHERE role != 'admin'");
        $users = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (empty($users)) return false;

        $sql = "INSERT INTO notifications (user_id, message, link) VALUES (?, ?, ?)";
        $insertStmt = $this->db->prepare($sql);

        // Transactional lock for bulk insert efficiency
        $this->db->beginTransaction();
        try {
            foreach ($users as $uid) {
                $insertStmt->execute([$uid, $message, $link]);
            }
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}

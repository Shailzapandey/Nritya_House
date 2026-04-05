<?php

namespace App\Controllers;

use App\Core\Database;
use PDO;

class NotificationController
{

    // Route: /notification
    public function index()
    {
        // Protect the route: Only logged-in users have notifications
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }

        $db = Database::getInstance()->getConnection();

        // Fetch user's notifications (assuming you have a notifications table)
        // If the table doesn't exist yet, we will handle it safely in the view.
        $notifications = [];
        try {
            $stmt = $db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 20");
            $stmt->execute([$_SESSION['user_id']]);
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Failsafe: If the table isn't built yet, the view won't crash
            $notifications = [];
        }

        require_once __DIR__ . '/../../views/pages/notifications.php';
    }
}

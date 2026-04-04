<?php

namespace App\Controllers;

use App\Models\Notification;

class NotificationController
{

    // Route: /notification/read
    public function read()
    {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            header("Location: " . BASE_URL . "/dashboard");
            exit;
        }

        $notifId = intval($_GET['id']);
        $model = new Notification();

        // Mark it read. We pass user_id so users can't mark OTHER people's notifications as read.
        $model->markAsRead($notifId, $_SESSION['user_id']);

        // Cleanly redirect them to the payload link, or back to the dashboard if none exists
        $redirectUrl = $_GET['ref'] ? BASE_URL . $_GET['ref'] : BASE_URL . "/dashboard";

        header("Location: " . $redirectUrl);
        exit;
    }
}

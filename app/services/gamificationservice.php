<?php

namespace App\Services;

use App\Core\Database;
use PDO;
use DateTime;

class GamificationService
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // NEW: The method your controller was looking for
    public function logDailyActivity($userId)
    {
        $stmt = $this->db->prepare("INSERT IGNORE INTO user_activity_logs (user_id, activity_date) VALUES (?, CURDATE())");
        return $stmt->execute([$userId]);
    }

    // EXISTING: Your streak calculator
    public function calculateCurrentStreak($userId)
    {
        $stmt = $this->db->prepare("SELECT activity_date FROM user_activity_logs WHERE user_id = ? ORDER BY activity_date DESC");
        $stmt->execute([$userId]);
        $activity_dates = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $current_streak = 0;

        if (!empty($activity_dates)) {
            $today = new DateTime('today');
            $yesterday = new DateTime('yesterday');
            $first_log = new DateTime($activity_dates[0]);

            // Streak is only alive if activity happened today or yesterday
            if ($first_log == $today || $first_log == $yesterday) {
                $expected_date = $first_log;

                foreach ($activity_dates as $date_string) {
                    $log_date = new DateTime($date_string);

                    if ($log_date == $expected_date) {
                        $current_streak++;
                        $expected_date->modify('-1 day');
                    } else {
                        break; // Chain broken
                    }
                }
            }
        }
        return $current_streak;
    }
}

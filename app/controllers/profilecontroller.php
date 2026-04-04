<?php

namespace App\Controllers;

use App\Core\Database;
use PDO;

class ProfileController
{

    // Route: /profile
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT full_name, email, experience_level FROM users WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../../views/pages/profile.php';
    }

    // Route: /profile/update
    public function update()
    {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }

        \App\Core\Security::verifyCsrf();

        $new_level = $_POST['experience_level'] ?? '';
        $allowed_levels = ['Beginner', 'Intermediate', 'Advanced'];

        if (!in_array($new_level, $allowed_levels)) {
            die("Security Error: Invalid level selection.");
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE users SET experience_level = ? WHERE user_id = ?");
        $stmt->execute([$new_level, $_SESSION['user_id']]);

        header("Location: " . BASE_URL . "/profile?msg=updated");
        exit;
    }
}

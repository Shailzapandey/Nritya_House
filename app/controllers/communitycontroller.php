<?php

namespace App\Controllers;

use App\Core\Database;
use PDO;

class communitycontroller
{

    // Route: /community
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }

        $db = Database::getInstance()->getConnection();

        // 1. Handle POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_content'])) {
            \App\Core\Security::verifyCsrf();
            $content = trim($_POST['post_content']);

            if (!empty($content)) {
                $stmt = $db->prepare("INSERT INTO community_posts (user_id, content) VALUES (?, ?)");
                $stmt->execute([$_SESSION['user_id'], $content]);
                header("Location: " . BASE_URL . "/community");
                exit;
            }
        }

        // 2. Fetch Feed (Notice we pull 'role' now)
        $sql = "SELECT cp.content, cp.created_at, u.full_name AS author_name, u.role 
                FROM community_posts cp
                INNER JOIN users u ON cp.user_id = u.user_id
                ORDER BY cp.created_at DESC";
        $stmt = $db->query($sql);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../../views/pages/community.php';
    }
}

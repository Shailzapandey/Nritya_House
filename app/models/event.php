<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Event
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Public Facing: Only show events that haven't happened yet
    public function getUpcoming()
    {
        $sql = "SELECT * FROM events WHERE event_date >= NOW() ORDER BY event_date ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Public Facing: Fetch past events for the photo gallery
    public function getPastEvents($limit = 6)
    {
        // We cast the limit safely to prevent injection since PDO sometimes struggles binding LIMIT clauses directly
        $limit = intval($limit);
        $sql = "SELECT * FROM events WHERE event_date < NOW() ORDER BY event_date DESC LIMIT $limit";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Admin Facing: Show everything
    public function getAll()
    {
        $sql = "SELECT * FROM events ORDER BY event_date DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Secure Insert
    public function create($title, $description, $eventDate, $location, $imageUrl = 'default_thumb.jpg')
    {
        $sql = "INSERT INTO events (title, description, event_date, location, image_url) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$title, $description, $eventDate, $location, $imageUrl]);
    }

    // Secure Delete
    public function delete($eventId)
    {
        $sql = "DELETE FROM events WHERE event_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$eventId]);
    }
}

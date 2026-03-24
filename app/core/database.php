<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $pdo;

    // Private constructor prevents direct object creation
    private function __construct()
    {
        // Read from the environment variables loaded in index.php
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $db   = $_ENV['DB_NAME'] ?? 'nritya_house';
        $user = $_ENV['DB_USER'] ?? 'root';
        $pass = $_ENV['DB_PASS'] ?? '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Strict error throwing
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Always return associative arrays
            PDO::ATTR_EMULATE_PREPARES   => false,                  // True prepared statements
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // In production, log this error instead of displaying it.
            // For now, we kill the script to prevent further execution on database failure.
            die("Database Connection Failed: " . $e->getMessage());
        }
    }

    // The Singleton gatekeeper
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Expose the raw PDO object for queries
    public function getConnection()
    {
        return $this->pdo;
    }
}

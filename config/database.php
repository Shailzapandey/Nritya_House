<?php
// config/database.php

$host = 'localhost';
$dbname = 'nritya_house';
$username = 'root'; // Default XAMPP username
$password = '';     // Default XAMPP password is blank

try {
    // 1. Establish the PDO connection
    // We use PDO (PHP Data Objects) because it allows for Prepared Statements, 
    // which is our primary defense against SQL Injection attacks.
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // 2. Set Error Mode to Exception
    // If a query fails, we want PHP to throw a fatal error so we can fix it immediately,
    // rather than failing silently and leaving us guessing.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // If you uncomment the line below, it will print on every page to prove it works.
    // echo "Database Connected Successfully!"; 

} catch (PDOException $e) {
    // If the connection fails, kill the page and show the error.
    die("Database Connection Failed: " . $e->getMessage());
}
?>
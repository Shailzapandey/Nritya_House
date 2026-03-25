<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Coupon
{
    private $db;
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function validateCode($code)
    {
        $stmt = $this->db->prepare("SELECT discount_percent FROM coupons WHERE code = ? AND is_active = 1");
        $stmt->execute([strtoupper(trim($code))]);
        return $stmt->fetchColumn();
    }
}

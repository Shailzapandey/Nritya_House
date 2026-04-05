<?php

namespace App\Controllers;

class LiveController
{
    // Route: /live
    public function index()
    {
        require_once __DIR__ . '/../../views/pages/live_classes.php';
    }
}

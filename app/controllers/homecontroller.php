<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        // In the future, this is where we will ask the Model for data.
        // For now, we just load the HTML view.
        require_once __DIR__ . '/../../views/pages/home.php';
    }
}

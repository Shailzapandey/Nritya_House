<?php

namespace App\Controllers;

use App\Models\Course;

class HomeController
{
    public function index()
    {
        $courseModel = new Course();

        // Fetch a few courses for the home page carousel
        // We use getAll() and slice it, or create a specific getFeatured method
        $allCourses = $courseModel->getAll();
        $featured_courses = array_slice($allCourses, 0, 6);

        // Pass the variable to the view
        require_once __DIR__ . '/../../views/pages/home.php';
    }
}

<?php

namespace App\Controllers;

use App\Models\Instructor;

class InstructorController
{

    // Route: /instructor
    // Displays the grid of all instructors
    public function index()
    {
        $instructorModel = new Instructor();
        $instructors = $instructorModel->getAll();

        require_once __DIR__ . '/../../views/pages/instructors.php';
    }

    // Route: /instructor/profile?id=1
    // Displays a specific instructor's bio and their courses
    public function profile()
    {
        if (!isset($_GET['id'])) {
            header("Location: " . BASE_URL . "/instructor");
            exit;
        }

        $id = intval($_GET['id']);
        $instructorModel = new Instructor();

        $instructor = $instructorModel->getById($id);
        if (!$instructor) {
            header("Location: " . BASE_URL . "/instructor");
            exit;
        }

        $courses = $instructorModel->getCoursesByInstructor($id);

        require_once __DIR__ . '/../../views/pages/instructor_profile.php';
    }
}

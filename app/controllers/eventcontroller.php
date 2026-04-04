<?php

namespace App\Controllers;

use App\Models\Event;

class EventController
{

    // Route: /event
    public function index()
    {
        $eventModel = new Event();

        $upcoming_events = $eventModel->getUpcoming();
        $past_events = $eventModel->getPastEvents(6); // Get the 6 most recent past events

        require_once __DIR__ . '/../../views/pages/events.php';
    }
}

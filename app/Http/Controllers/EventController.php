<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::published()->orderBy('start_at', 'asc')->paginate(9);
        return view('events', compact('events'));
    }

    public function show($slug)
    {
        $event = Event::published()->where('slug', $slug)->firstOrFail();
        return view('event-detail', compact('event'));
    }
}



<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // tampilkan semua event publik
    public function index()
    {
        $events = Event::with('organizer', 'venue')
            ->where('status', 'published')
            ->orderBy('start_at', 'asc')
            ->get();

        return view('events.index', compact('events'));
    }

    // tampilkan detail event berdasarkan slug
    public function show($slug)
    {
        $event = \App\Models\Event::with('ticketTypes', 'venue')->where('slug', $slug)->firstOrFail();
    return view('events.show', compact('event'));
    }
    
}

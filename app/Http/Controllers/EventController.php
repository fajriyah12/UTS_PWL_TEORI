<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // tampilkan semua event publik
    public function index(Request $request)
{
    $organizer = auth()->user()->organizer;
    
    $query = $organizer->events()->with('ticketTypes')->latest();
    
    // Search filter
    if ($request->has('search') && $request->search != '') {
        $query->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('description', 'like', '%' . $request->search . '%');
    }
    
    // Status filter
    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }
    
    // Category filter
    if ($request->has('category') && $request->category != '') {
        $query->where('category', $request->category);
    }
    
    $events = $query->paginate(10)->withQueryString();
    
    return view('organizer.events.index', compact('events'));
}

    // tampilkan detail event berdasarkan slug
    public function show($slug)
    {
        $event = \App\Models\Event::with('ticketTypes', 'venue')->where('slug', $slug)->firstOrFail();
    return view('events.show', compact('event'));
    }
    
}

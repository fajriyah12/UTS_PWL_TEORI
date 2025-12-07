<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Venue;
use App\Models\TicketType;
use App\Http\Requests\Organizer\StoreEventRequest;
use App\Http\Requests\Organizer\UpdateEventRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $organizer = auth()->user()->organizer;
        $events = $organizer->events()->latest()->paginate(10);
        
        return view('organizer.events.index', compact('events'));
    }
    
    public function create()
    {
        $venues = Venue::all();
        return view('organizer.events.create', compact('venues'));
    }
    
    public function store(StoreEventRequest $request)
    {
        $organizer = auth()->user()->organizer;
        
        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
        }
        
        // Create event
        $event = Event::create([
            'organizer_id' => $organizer->id,
            'venue_id' => $request->venue_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . Str::random(6),
            'description' => $request->description,
            'image' => $imagePath,
            'location' => $request->location,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => $request->status,
        ]);
        
        // Create ticket types
        foreach ($request->ticket_types as $ticketData) {
            TicketType::create([
                'event_id' => $event->id,
                'name' => $ticketData['name'],
                'price' => $ticketData['price'],
                'quota' => $ticketData['quota'],
                'per_user_limit' => $ticketData['per_user_limit'],
                'sales_start' => $ticketData['sales_start'] ?? null,
                'sales_end' => $ticketData['sales_end'] ?? null,
            ]);
        }
        
        return redirect()->route('organizer.events.index')
            ->with('success', 'Event berhasil dibuat!');
    }
    
    public function show(Event $event)
    {
        // Authorization: ensure organizer owns this event
        $this->authorize('view', $event);
        
        $event->load('ticketTypes', 'venue');
        return view('organizer.events.show', compact('event'));
    }
    
    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        
        $venues = Venue::all();
        $event->load('ticketTypes');
        
        return view('organizer.events.edit', compact('event', 'venues'));
    }
    
    public function update(UpdateEventRequest $request, Event $event)
    {
        $this->authorize('update', $event);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image) {
                \Storage::disk('public')->delete($event->image);
            }
            
            $imagePath = $request->file('image')->store('events', 'public');
            $event->image = $imagePath;
        }
        
        // Update event
        $event->update([
            'venue_id' => $request->venue_id,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => $request->status,
        ]);
        
        // Update or create ticket types
        if ($request->has('ticket_types')) {
            // Delete removed ticket types
            $currentTicketIds = $event->ticketTypes->pluck('id')->toArray();
            $updatedTicketIds = collect($request->ticket_types)
                ->pluck('id')
                ->filter()
                ->toArray();
                
            $ticketsToDelete = array_diff($currentTicketIds, $updatedTicketIds);
            TicketType::whereIn('id', $ticketsToDelete)->delete();
            
            // Update or create ticket types
            foreach ($request->ticket_types as $ticketData) {
                if (isset($ticketData['id']) && $ticketData['id']) {
                    // Update existing
                    $ticket = TicketType::find($ticketData['id']);
                    if ($ticket && $ticket->event_id == $event->id) {
                        $ticket->update([
                            'name' => $ticketData['name'],
                            'price' => $ticketData['price'],
                            'quota' => $ticketData['quota'],
                            'per_user_limit' => $ticketData['per_user_limit'],
                            'sales_start' => $ticketData['sales_start'] ?? null,
                            'sales_end' => $ticketData['sales_end'] ?? null,
                        ]);
                    }
                } else {
                    // Create new
                    TicketType::create([
                        'event_id' => $event->id,
                        'name' => $ticketData['name'],
                        'price' => $ticketData['price'],
                        'quota' => $ticketData['quota'],
                        'per_user_limit' => $ticketData['per_user_limit'],
                        'sales_start' => $ticketData['sales_start'] ?? null,
                        'sales_end' => $ticketData['sales_end'] ?? null,
                    ]);
                }
            }
        }
        
        return redirect()->route('organizer.events.index')
            ->with('success', 'Event berhasil diperbarui!');
    }
    
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        
        // Delete event image if exists
        if ($event->image) {
            \Storage::disk('public')->delete($event->image);
        }
        
        $event->delete();
        
        return redirect()->route('organizer.events.index')
            ->with('success', 'Event berhasil dihapus!');
    }
}
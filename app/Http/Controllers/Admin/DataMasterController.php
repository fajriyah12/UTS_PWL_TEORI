<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketType;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;


class DataMasterController extends Controller
{
    public function index()
    {
        $ticketTypes = TicketType::with('event')
            ->latest()
            ->paginate(10);
        
        $users = User::latest()->paginate(10);

        return view('admin.datamaster', compact('ticketTypes', 'users'));
    }

    public function createTicket()
{
    $events = Event::all();
    return view('admin.ticket.create', compact('events'));
}

public function editTicket(TicketType $ticketType)
{
    $events = Event::all();
    return view('admin.ticket.edit', compact('ticketType', 'events'));
}


    public function searchTickets(Request $request)
    {
        $query = TicketType::with('event');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('event', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            })->orWhere('name', 'like', "%{$search}%");
        }

        if ($request->filled('event') && $request->input('event') !== 'all') {
            $query->where('event_id', $request->input('event'));
        }

       if ($request->filled('status') && $request->input('status') !== 'all') {
    $now = Carbon::now();

    if ($request->input('status') === 'active') {
        $query->where('sales_start', '<=', $now)
              ->where('sales_end', '>=', $now);
    } elseif ($request->input('status') === 'inactive') {
        $query->where(function ($q) use ($now) {
            $q->where('sales_end', '<', $now)
              ->orWhere('sales_start', '>', $now);
        });
    }
}


        $tickets = $query->paginate(10);
        return view('admin.partials.tickets-table', compact('tickets'));
    }

    public function searchUsers(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        if ($request->filled('role') && $request->input('role') !== 'all') {
            $query->where('role', $request->input('role'));
        }

        $users = $query->paginate(10);
        return view('admin.partials.users-table', compact('users'));
    }

    public function storeTicket(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
        ]);

        TicketType::create($validated);

        return response()->json(['message' => 'Ticket created successfully'], 201);
    }

    public function updateTicket(Request $request, TicketType $ticketType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
        ]);

        $ticketType->update($validated);

        return response()->json(['message' => 'Ticket updated successfully']);
    }

    public function destroyTicket(TicketType $ticketType)
    {
        $ticketType->delete();
        return response()->json(['message' => 'Ticket deleted successfully']);
    }

    public function destroyUser(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:user,admin,organizer',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => \Hash::make($validated['password']),
            'email_verified_at' => now(), // Auto verify for admin created users
        ]);

        return response()->json(['message' => 'User created successfully'], 201);
    }
}

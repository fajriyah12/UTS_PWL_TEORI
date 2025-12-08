<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\Request;

class UserTransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $allOrders = Order::with(['orderItems.ticketType.event.organizer']) // Added organizer relation if needed
            ->where('buyer_email', $user->email)
            ->latest()
            ->get();

        $successfulOrders = $allOrders->where('status', 'paid');
        $pendingOrders = $allOrders->where('status', '!=', 'paid');

        return view('transactions.index', compact('successfulOrders', 'pendingOrders'));
    }

    public function tickets(Request $request)
    {
        $user = $request->user();

        $tickets = Ticket::whereHas('orderItem.order', function ($query) use ($user) {
            $query->where('buyer_email', $user->email);
        })
        ->whereIn('status', ['issued', 'checked_in'])
        ->with(['ticketType.event'])
        ->get();

        // Upcoming: Future events AND Not checked in
        $upcomingTickets = $tickets->filter(function ($ticket) {
            return $ticket->ticketType->event->start_time >= now() && $ticket->status !== 'checked_in';
        });

        // Past: Past events OR Checked in
        $pastTickets = $tickets->filter(function ($ticket) {
            return $ticket->ticketType->event->start_time < now() || $ticket->status === 'checked_in';
        });

        return view('transactions.tickets', compact('upcomingTickets', 'pastTickets'));
    }

    public function showTicket(Request $request, Ticket $ticket)
    {
        $user = $request->user();

        $buyerEmail = optional($ticket->orderItem->order)->buyer_email;
        if ($buyerEmail !== $user->email) {
            abort(403);
        }

        return view('transactions.show-ticket', compact('ticket'));
    }
}


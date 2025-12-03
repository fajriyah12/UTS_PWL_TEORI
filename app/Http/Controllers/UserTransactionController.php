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

        $orders = Order::with(['orderItems.ticketType.event'])
            ->where('buyer_email', $user->email)
            ->latest()
            ->paginate(10);

        return view('transactions.index', compact('orders'));
    }

    public function tickets(Request $request)
    {
        $user = $request->user();

        // Ambil semua tiket milik user
        // Asumsi: Ticket berelasi dengan OrderItem -> Order (buyer_email)
        // Dan TicketType -> Event
        $tickets = Ticket::whereHas('orderItem.order', function ($query) use ($user) {
            $query->where('buyer_email', $user->email);
        })
        ->with(['ticketType.event'])
        ->get();

        $upcomingTickets = $tickets->filter(function ($ticket) {
            return $ticket->ticketType->event->start_time >= now();
        });

        $pastTickets = $tickets->filter(function ($ticket) {
            return $ticket->ticketType->event->start_time < now();
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


<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function verify(Request $request, \App\Models\Event $event)
    {
        $this->authorize('view', $event);
        
        $request->validate([
            'token' => 'required|string'
        ]);
        
        $ticket = \App\Models\Ticket::where('qr_token', $request->token)
                                    ->orWhere('serial', $request->token)
                                    ->first();
                                    
        if (!$ticket) {
            return back()->with('error', 'Tiket tidak ditemukan.');
        }
        
        // Ensure ticket belongs to this event
        if ($ticket->ticketType->event_id !== $event->id) {
             return back()->with('error', 'Tiket ini bukan untuk event ini.');
        }
        
        if ($ticket->status === 'checked_in') {
             return back()->with('error', 'Tiket sudah digunakan sebelumnya.');
        }

        // Allow valid OR pending (assuming on-site payment or immediate usage)
        if (!in_array($ticket->status, ['issued', 'pending'])) {
             return back()->with('error', 'Tiket tidak valid/dibatalkan.');
        }
        
        // Mark as checked_in (used)
        $ticket->update(['status' => 'checked_in']);
        
        return back()->with('success', 'Verifikasi Berhasil! Tiket atas nama ' . $ticket->holder_name);
    }
}

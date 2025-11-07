<?php

namespace App\Http\Controllers;

use App\Models\Event;

class HomeController extends Controller
{
    public function __invoke()
    {
        $events = Event::with(['venue', 'ticketTypes'])
            ->published()                               // scope (lihat di langkah 2)
            ->withMin('ticketTypes', 'price')           // dapatkan harga termurah per event
            ->orderBy('start_at', 'asc')
            ->take(9)
            ->get();

        return view('welcome', compact('events'));
    }
}

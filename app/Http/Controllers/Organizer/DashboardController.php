<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $organizer = Auth::user()->organizer;
        $events = $organizer?->events()->with('ticketTypes')->get();

        return view('organizer.dashboard', compact('organizer', 'events'));
    }
}

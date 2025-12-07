<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class DashboardController extends Controller
{
    public function index()

    {

        $user = auth()->user();

        $organizer = $user->organizer;



        // If organizer profile doesn't exist, create one

        if (!$organizer) {

            $organizer = \App\Models\Organizer::create([

                'user_id' => (string) $user->id, // Ensure it's cast to string

                'company_name' => $user->name . "'s Organizer",

                'phone' => $user->phone,

                'address' => null,

                'is_verified' => true, // Set to true since no admin verification needed

                'bank_account' => null,

                'bank_name' => null,

            ]);

        } else {

            // If organizer exists but has old schema (missing company_name), update it

            if (!isset($organizer->company_name) && isset($organizer->name)) {

                $organizer->update([

                    'company_name' => $organizer->name,

                    'phone' => $organizer->contact_phone,

                    'is_verified' => true,

                ]);

            }

        }



        // Get organizer's events

        $events = $organizer->events()->with('ticketTypes')->latest()->get();
        
        // Calculate statistics
        $totalEvents = $events->count();
        $totalTicketsSold = $events->sum(function ($event) {
            return $event->ticketTypes->sum('sold');
        });
        $totalRevenue = $events->sum(function ($event) {
            return $event->ticketTypes->sum(function ($ticketType) {
                return $ticketType->price * $ticketType->sold;
            });
        });
        
        // Recent events (last 5)
        $recentEvents = $events->take(5);
        
        return view('organizer.dashboard.index', compact(
            'totalEvents',
            'totalTicketsSold',
            'totalRevenue',
            'recentEvents'
        ));
    }
    
    public function statistics()
    {
        $organizer = auth()->user()->organizer;
        $events = $organizer->events()->with('ticketTypes')->get();
        
        // Prepare data for charts
        $eventNames = [];
        $ticketsSold = [];
        $revenueData = [];
        
        foreach ($events as $event) {
            $eventNames[] = $event->title;
            $ticketsSold[] = $event->ticketTypes->sum('sold');
            $revenueData[] = $event->ticketTypes->sum(function ($ticketType) {
                return $ticketType->price * $ticketType->sold;
            });
        }
        
        return view('organizer.dashboard.statistics', compact(
            'events',
            'eventNames',
            'ticketsSold',
            'revenueData'
        ));
    }
}
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
            $name = $user->name . "'s Organizer";
            $organizer = \App\Models\Organizer::create([
                'user_id' => (string) $user->id, // Ensure it's cast to string
                'name' => $name,
                'slug' => \Illuminate\Support\Str::slug($name) . '-' . \Illuminate\Support\Str::random(5),
                'company_name' => $name,
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
        
        // Refresh the relationship on the authenticated user instance
        // This ensures that subsequent calls to auth('staff')->user()->organizer (like in the layout)
        // return the newly created/updated organizer instead of null.
        if ($organizer) {
            auth('staff')->user()->setRelation('organizer', $organizer);
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
    
    public function statistics(Request $request)
    {
        $organizer = auth()->user()->organizer;
        $events = $organizer->events()
            ->with(['ticketTypes' => function($q) {
                $q->withCount('tickets')->with(['orderItems.order', 'orderItems.tickets']);
            }])
            ->get();
        
        // Prepare data for charts (Total per event)
        $eventNames = [];
        $ticketsSold = [];
        $revenueData = [];
        
        foreach ($events as $event) {
            $eventNames[] = $event->title;
            $soldCount = 0;
            $revenue = 0;
            
            foreach ($event->ticketTypes as $tt) {
                // Sum sold using withCount result
                $soldCount += $tt->tickets_count;
                
                // Revenue from OrderItems
                 foreach ($tt->orderItems as $item) {
                     $revenue += $item->subtotal;
                 }
            }
            
            $ticketsSold[] = $soldCount;
            $revenueData[] = $revenue;
        }

        // Weekly Sales (Last 7 days)
        $weeklySales = [];
        $weeklyRevenue = [];
        $weekDates = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $weekDates[] = now()->subDays($i)->format('d M');
            $weeklySales[$date] = 0;
            $weeklyRevenue[$date] = 0;
        }

        // Monthly Sales (Last 12 months)
        $monthlySales = [];
        $monthlyRevenue = [];
        $monthLabels = [];
        for ($i = 11; $i >= 0; $i--) {
             $month = now()->subMonths($i)->format('Y-m');
             $monthLabels[] = now()->subMonths($i)->format('M Y');
             $monthlySales[$month] = 0;
             $monthlyRevenue[$month] = 0;
        }

        // Aggregate Data
        foreach ($events as $event) {
            foreach ($event->ticketTypes as $tt) {
                foreach ($tt->orderItems as $item) {
                    // Check order date
                    if ($item->order) {
                        $orderDate = $item->order->created_at;
                        $dateKey = $orderDate->format('Y-m-d');
                        $monthKey = $orderDate->format('Y-m');
                        
                        // Weekly
                        if (isset($weeklySales[$dateKey])) {
                            $weeklySales[$dateKey] += $item->qty;
                            $weeklyRevenue[$dateKey] += $item->subtotal;
                        }
                        
                        // Monthly
                        if (isset($monthlySales[$monthKey])) {
                            $monthlySales[$monthKey] += $item->qty;
                            $monthlyRevenue[$monthKey] += $item->subtotal;
                        }
                    }
                }
            }
        }

        return view('organizer.dashboard.statistics', compact(
            'events',
            'eventNames',
            'ticketsSold',
            'revenueData',
            'weekDates',
            'weeklySales',
            'weeklyRevenue',
            'monthLabels',
            'monthlySales',
            'monthlyRevenue'
        ));
    }

    public function exportBuyers(Event $event)
    {
        $this->authorize('view', $event);
        
        $fileName = 'buyers-event-' . $event->slug . '-' . date('Y-m-d') . '.csv';
        
        $tickets = \App\Models\Ticket::whereHas('ticketType', function($q) use ($event) {
            $q->where('event_id', $event->id);
        })->with(['orderItem.order', 'ticketType'])->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Ticket Serial', 'Ticket Type', 'Holder Name', 'Holder Email', 'Order ID', 'Buyer Name', 'Purchase Date', 'Status');

        $callback = function() use($tickets, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tickets as $ticket) {
                $row['Ticket Serial']  = $ticket->serial;
                $row['Ticket Type']    = $ticket->ticketType->name;
                $row['Holder Name']    = $ticket->holder_name;
                $row['Holder Email']   = $ticket->holder_email;
                $row['Order ID']       = $ticket->orderItem->order->order_code ?? '-';
                $row['Buyer Name']     = $ticket->orderItem->order->buyer_name ?? '-';
                $row['Purchase Date']  = $ticket->created_at->format('Y-m-d H:i');
                $row['Status']         = $ticket->status;

                fputcsv($file, array(
                    $row['Ticket Serial'],
                    $row['Ticket Type'],
                    $row['Holder Name'],
                    $row['Holder Email'],
                    $row['Order ID'],
                    $row['Buyer Name'],
                    $row['Purchase Date'],
                    $row['Status']
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
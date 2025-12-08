<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\TicketType;

class HomeController extends Controller
{
public function __invoke(Request $request)
{
    $type = $request->query('type');

    $types = \App\Models\TicketType::query()
        ->select('name')->distinct()->orderBy('name')->pluck('name');

    $search = $request->query('search');

    $events = \App\Models\Event::with(['ticketTypes']) // Hapus 'venue' dari with
        ->published()
        ->when($type, fn($q) => $q->whereHas('ticketTypes', fn($t) => $t->where('name', $type)))
        ->when($search, function($q) use ($search) {
            $string = strtolower($search);
            $q->where(function($query) use ($string) {
                $query->whereRaw('LOWER(title) like ?', ['%'.$string.'%'])
                      ->orWhereRaw('LOWER(description) like ?', ['%'.$string.'%']);
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(32)
        ->withQueryString();

    if ($request->ajax()) {
        return view('components.events.grid', compact('events', 'type'));
    }

    return view('welcome', compact('events', 'types', 'type'));
}
}

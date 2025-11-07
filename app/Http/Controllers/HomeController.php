<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\TicketType;

class HomeController extends Controller
{
    public function __invoke(Request $request)
{
    $type  = $request->query('type');

    $types = \App\Models\TicketType::query()
        ->select('name')->distinct()->orderBy('name')->pluck('name');

    $events = \App\Models\Event::with(['venue','ticketTypes'])
        ->published()
        ->withMin('ticketTypes','price')
        ->when($type, fn($q) => $q->whereHas('ticketTypes', fn($t) => $t->where('name',$type)))
        ->orderBy('created_at','desc')
        ->paginate(32)                 
        ->withQueryString();           

    return view('welcome', compact('events','types','type'));
}
}

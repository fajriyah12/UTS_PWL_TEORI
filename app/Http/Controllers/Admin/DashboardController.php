<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Event;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $totalEvent = Event::count();
        $totalOrder = Order::count();
        $income = Order::where('status', 'paid')->sum('total_amount');

        return view('admin.dashboard', compact('totalEvent', 'totalOrder', 'income'));
    }
}

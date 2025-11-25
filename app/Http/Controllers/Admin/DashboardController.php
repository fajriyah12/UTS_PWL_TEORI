<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Event;
use App\Models\Organizer;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        // Total event yang published (validation: kolom status harus ada di tabel events)
        $totalEvents = Event::count();

        // Total tiket terjual (dari tabel tickets)
        $totalTicketsSold = DB::table('tickets')
            ->whereNotNull('order_item_id')
            ->count();

        // Total pendapatan (gunakan total_price karena itu yang ada di migration)
        $totalRevenue = Order::sum('total_price');

        // Total organizer
        $totalOrganizers = Organizer::count();

        // Recent transaksi (hapus filter status karena kolom tidak ada)
        $recentTransactions = Order::with(['orderItems.ticketType.event'])
            ->latest()
            ->take(5)
            ->get();

        // Chart data - ticket sales by month (hapus where status)
        $ticketSalesByMonth = DB::table('orders')
    ->selectRaw('EXTRACT(MONTH FROM created_at) as month, COUNT(id) as total')
    ->whereRaw('EXTRACT(YEAR FROM created_at) = ?', [date('Y')])
    ->groupByRaw('EXTRACT(MONTH FROM created_at)')
    ->orderByRaw('EXTRACT(MONTH FROM created_at)')
    ->get();


        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $chartData = array_fill(0, 12, 0);

        foreach ($ticketSalesByMonth as $sale) {
            $chartData[$sale->month - 1] = $sale->total;
        }

        return view('admin.dashboard', compact(
            'totalEvents',
            'totalTicketsSold',
            'totalRevenue',
            'totalOrganizers',
            'recentTransactions',
            'chartLabels',
            'chartData'
        ));
    }
}

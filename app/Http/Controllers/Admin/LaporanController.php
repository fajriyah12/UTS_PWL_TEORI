<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        // Total pendapatan
        $totalRevenue = Order::sum('total_price');

        // Total tiket terjual
        $totalTickets = DB::table('tickets')
            ->whereNotNull('order_item_id')
            ->count();

        // Total event
        $totalEvents = Event::count();

        // Total transaksi
        $totalOrders = Order::count();

        // Grafik penjualan per bulan
        $ticketSalesByMonth = DB::table('orders')
            ->selectRaw('EXTRACT(MONTH FROM created_at) as month, COUNT(id) as total')
            ->whereRaw('EXTRACT(YEAR FROM created_at) = ?', [date('Y')])
            ->groupByRaw('EXTRACT(MONTH FROM created_at)')
            ->orderByRaw('EXTRACT(MONTH FROM created_at)')
            ->get();

        $chartLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $chartData = array_fill(0, 12, 0);

        foreach ($ticketSalesByMonth as $sale) {
            $chartData[$sale->month - 1] = $sale->total;
        }

        // Tabel laporan transaksi (join untuk event)
        $transactions = Order::with(['orderItems.ticketType.event'])
            ->latest()
            ->paginate(10);

        return view('admin.laporan.index', compact(
            'totalRevenue',
            'totalTickets',
            'totalEvents',
            'totalOrders',
            'chartLabels',
            'chartData',
            'transactions'
        ));
    }
}

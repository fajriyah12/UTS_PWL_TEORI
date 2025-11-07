<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketType;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;

class CheckoutController extends Controller
{
    public function create($ticketTypeId)
    {
        $ticket = TicketType::with('event')->findOrFail($ticketTypeId);
        return view('checkout.create', compact('ticket'));
    }

    public function store(Request $request, $ticketTypeId)
    {
        $ticket = TicketType::with('event')->findOrFail($ticketTypeId);

        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'quantity' => 'required|integer|min:1|max:' . $ticket->per_user_limit,
        ]);

        $orderId = 'ORREA-' . strtoupper(Str::random(8));
        $total = $ticket->price * $validated['quantity'];

        // simpan order dummy (optional)
        Order::create([
    'ticket_type_id' => $ticket->id,
    'buyer_name' => $validated['name'],
    'buyer_email' => $validated['email'],
    'quantity' => $validated['quantity'],
    'total_price' => $total,
    'order_code' => $orderId,
]);

        // konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // data transaksi
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $validated['name'],
                'email' => $validated['email'],
            ],
            'item_details' => [
                [
                    'id' => $ticket->id,
                    'price' => $ticket->price,
                    'quantity' => $validated['quantity'],
                    'name' => $ticket->event->title . ' - ' . $ticket->name,
                ],
            ],
        ];

        // generate Snap Token
        $snapToken = Snap::getSnapToken($params);

        // kirim ke view untuk ditampilkan via Midtrans Snap.js
        return view('checkout.payment', compact('snapToken', 'orderId', 'total'));
    }
}

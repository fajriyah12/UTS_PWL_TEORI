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
    public function create(Request $request, $ticketTypeId)
    {
        $ticket = TicketType::with('event')->findOrFail($ticketTypeId);
        $quantity = $request->query('quantity', 1);
        return view('checkout.create', compact('ticket', 'quantity'));
    }

    public function store(Request $request, $ticketTypeId)
    {
        $ticket = TicketType::with('event')->findOrFail($ticketTypeId);

        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'quantity' => 'required|integer|min:1|max:' . $ticket->per_user_limit,
            'visitors' => 'required|array|min:1',
            'visitors.*.name' => 'required|string',
            'visitors.*.email' => 'required|email',
            'visitors.*.dob' => 'required|date',
            'visitors.*.gender' => 'required|in:male,female',
            'visitors.*.phone' => 'required|string',
            'visitors.*.institution' => 'required|string',
            'visitors.*.occupation' => 'required|string',
        ]);

        $orderId = 'ORREA-' . strtoupper(Str::random(8));
        $total = $ticket->price * $validated['quantity'];

        // simpan order dummy (optional)
        $order = Order::create([
            'ticket_type_id' => $ticket->id,
            'buyer_name' => $validated['name'],
            'buyer_email' => $validated['email'],
            'quantity' => $validated['quantity'],
            'total_price' => $total,
            'order_code' => $orderId,
        ]);

        // Create OrderItem
        $orderItem = \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'ticket_type_id' => $ticket->id,
            'qty' => $validated['quantity'],
            'unit_price' => $ticket->price,
            'subtotal' => $total,
        ]);

        // Create Pending Tickets with Visitor Details
        foreach ($validated['visitors'] as $index => $visitor) {
            \App\Models\Ticket::create([
                'order_item_id' => $orderItem->id,
                'ticket_type_id' => $ticket->id,
                'serial' => $orderId . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'qr_token' => Str::random(32),
                'status' => 'pending', // Pending payment
                'holder_name' => $visitor['name'],
                'holder_email' => $visitor['email'],
                'date_of_birth' => $visitor['dob'],
                'gender' => $visitor['gender'],
                'phone' => $visitor['phone'],
                'institution' => $visitor['institution'],
                'occupation' => $visitor['occupation'],
            ]);
        }

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

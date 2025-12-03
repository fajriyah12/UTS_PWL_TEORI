<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FixTicketController extends Controller
{
    public function fix()
    {
        $orders = Order::with(['orderItems', 'ticketType'])->get();
        $count = 0;

        foreach ($orders as $order) {
            // 1. Ensure OrderItem exists
            $orderItem = OrderItem::where('order_id', $order->id)->first();
            
            if (!$orderItem) {
                // Create OrderItem if missing
                $ticketType = TicketType::find($order->ticket_type_id);
                if (!$ticketType) continue; // Skip if ticket type deleted

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'ticket_type_id' => $order->ticket_type_id,
                    'qty' => $order->quantity,
                    'unit_price' => $order->total_price / $order->quantity,
                    'subtotal' => $order->total_price,
                ]);
            }

            // 2. Ensure Tickets exist
            $currentTicketCount = Ticket::where('order_item_id', $orderItem->id)->count();
            
            if ($currentTicketCount < $order->quantity) {
                $needed = $order->quantity - $currentTicketCount;
                
                for ($i = 0; $i < $needed; $i++) {
                    $serial = $order->order_code . '-' . str_pad($currentTicketCount + $i + 1, 3, '0', STR_PAD_LEFT);
                    
                    // Avoid duplicate serials just in case
                    if (Ticket::where('serial', $serial)->exists()) continue;

                    Ticket::create([
                        'order_item_id' => $orderItem->id,
                        'ticket_type_id' => $order->ticket_type_id,
                        'serial' => $serial,
                        'qr_token' => Str::random(32),
                        'status' => 'issued',
                        'holder_name' => $order->buyer_name,
                        'holder_email' => $order->buyer_email,
                    ]);
                    $count++;
                }
            }
        }

        return "Fixed! Generated $count missing tickets. <a href='/transactions/tickets'>Go to Tickets</a>";
    }
}

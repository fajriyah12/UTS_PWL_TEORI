<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\TicketType;
use App\Models\OrderItem;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Konfigurasi Midtrans
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');

        // 1. Terima & parse notifikasi Midtrans
        try {
            $notification = new Notification();
        } catch (\Throwable $e) {
            Log::error('Midtrans callback parse failed', [
                'payload' => $request->all(),
                'error'   => $e->getMessage(),
            ]);

            return response()->json(['message' => 'Invalid notification'], 400);
        }

        $orderCode         = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus       = property_exists($notification, 'fraud_status')
            ? $notification->fraud_status
            : null;

        // 2. Cek status transaksi
        if (! in_array($transactionStatus, ['capture', 'settlement']) || $fraudStatus === 'deny') {
            return response()->json(['message' => 'Transaction not completed'], 200);
        }

        // 3–5. Update quota dengan DB transaction + log error jika gagal
        try {
            DB::transaction(function () use ($orderCode) {
                // Ambil order + ticket type dan lock row-nya
                $order = Order::where('order_code', $orderCode)
                    ->lockForUpdate()
                    ->firstOrFail();

                $ticketType = TicketType::where('id', $order->ticket_type_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Pastikan quota cukup
                if ($ticketType->quota < $order->quantity) {
                    throw new \RuntimeException('Not enough ticket quota');
                }

                // Kurangi quota sesuai jumlah tiket yang dibeli
                $ticketType->quota = $ticketType->quota - $order->quantity;
                $ticketType->save();

                // Create OrderItem if not exists (for backward compatibility or safety)
                $orderItem = OrderItem::firstOrCreate(
                    ['order_id' => $order->id],
                    [
                        'ticket_type_id' => $ticketType->id,
                        'qty' => $order->quantity,
                        'unit_price' => $order->total_price / $order->quantity,
                        'subtotal' => $order->total_price,
                    ]
                );

                // Create Tickets
                for ($i = 0; $i < $order->quantity; $i++) {
                    // Check if ticket already exists to avoid duplicates
                    $serial = $order->order_code . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);
                    if (Ticket::where('serial', $serial)->exists()) {
                        continue;
                    }

                    Ticket::create([
                        'order_item_id' => $orderItem->id,
                        'ticket_type_id' => $ticketType->id,
                        'serial' => $serial,
                        'qr_token' => Str::random(32),
                        'status' => 'issued',
                        'holder_name' => $order->buyer_name,
                        'holder_email' => $order->buyer_email,
                    ]);
                }
            });
        } catch (\Throwable $e) {
            Log::error('Failed to update ticket quota after Midtrans payment', [
                'order_code' => $orderCode,
                'error'      => $e->getMessage(),
            ]);

            return response()->json(['message' => 'Failed to update quota'], 500);
        }

        return response()->json(['message' => 'OK'], 200);
    }
}
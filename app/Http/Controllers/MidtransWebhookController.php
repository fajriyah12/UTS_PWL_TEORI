<?php                                                                                                                                                  
                                                                                                                                                         
  namespace App\Http\Controllers;                                                                                                                        
                                                                                                                                                         
  use App\Models\Order;                                                                                                                                  
  use App\Models\TicketType;                                                                                                                             
  use Illuminate\Http\Request;                                                                                                                           
  use Illuminate\Support\Facades\DB;                                                                                                                     
  use Illuminate\Support\Facades\Log;                                                                                                                    
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
<?php

namespace App\Models;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {
    use HasUuid;
    protected $fillable=['order_id','ticket_type_id','qty','unit_price','subtotal'];
    public function order(){ return $this->belongsTo(Order::class); }
    public function ticketType(){ return $this->belongsTo(TicketType::class); }
    public function tickets(){ return $this->hasMany(Ticket::class); }
    public function event()
    {
        return $this->hasOneThrough(
            Event::class,          // Model tujuan
            TicketType::class,     // Model perantara
            'id',                  // PK di ticket_types
            'id',                  // PK di events
            'ticket_type_id',      // FK pada order_items → ticket_types.id
            'event_id'             // FK pada ticket_types → events.id
        );
    }
}


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
}

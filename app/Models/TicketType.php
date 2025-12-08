<?php

namespace App\Models;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketType extends Model {
    use HasUuid;
    protected $fillable=['event_id','name','quota','sold','per_user_limit','price','sales_start','sales_end'];
    protected $casts=['sales_start'=>'datetime','sales_end'=>'datetime'];
    public function event(){ return $this->belongsTo(Event::class); }
    public function orderItems(){ return $this->hasMany(OrderItem::class); }
    public function tickets(){ return $this->hasMany(Ticket::class); }
}

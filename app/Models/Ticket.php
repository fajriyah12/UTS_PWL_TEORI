<?php

namespace App\Models;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {
use HasUuid;
    protected $fillable=['order_item_id','ticket_type_id','serial','qr_token','status','holder_name','holder_email','date_of_birth','gender','phone','institution','occupation'];
    public function orderItem(){ return $this->belongsTo(OrderItem::class); }
    public function ticketType(){ return $this->belongsTo(TicketType::class); }
    public function checkins(){ return $this->hasMany(Checkin::class); }
}


<?php

namespace App\Models;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model {
use HasUuid;
    protected $fillable=['ticket_id','operator_id','gate','device_id','checked_in_at'];
    protected $casts=['checked_in_at'=>'datetime'];
    public function ticket(){ return $this->belongsTo(Ticket::class); }
    public function operator(){ return $this->belongsTo(User::class,'operator_id'); }
}


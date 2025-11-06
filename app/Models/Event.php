<?php

namespace App\Models;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model {
    use HasUuid;
    protected $fillable=['organizer_id','venue_id','title','slug','description','banner_path','start_at','end_at','status'];
    protected $casts=['start_at'=>'datetime','end_at'=>'datetime'];
    public function organizer(){ return $this->belongsTo(Organizer::class); }
    public function venue(){ return $this->belongsTo(Venue::class); }
    public function ticketTypes(){ return $this->hasMany(TicketType::class); }
}


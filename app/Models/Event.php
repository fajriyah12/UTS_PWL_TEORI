<?php

namespace App\Models;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model {
    use HasUuid;
    protected $fillable=['organizer_id','venue_id','title','slug','description','image','location','start_time','end_time','status'];
    public function scopePublished($q) { return $q->where('status', 'published'); }
    protected $casts=['start_time'=>'datetime','end_time'=>'datetime'];
    public function organizer(){ return $this->belongsTo(Organizer::class); }
    public function venue(){ return $this->belongsTo(Venue::class); }
    public function ticketTypes(){ return $this->hasMany(TicketType::class); }

    public function getBannerUrlAttribute(): string
{
    return $this->image
        ? asset('storage/'.$this->image)
        : 'https://images.unsplash.com/photo-1507874457470-272b3c8d8ee2'; 
}
}


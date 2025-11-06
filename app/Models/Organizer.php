<?php

namespace App\Models;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizer extends Model {
    use HasUuid;
    protected $fillable = ['user_id','name','slug','contact_email','contact_phone','bio','logo_path'];
    public function user(){ return $this->belongsTo(User::class); }
    public function venues(){ return $this->hasMany(Venue::class); }
    public function events(){ return $this->hasMany(Event::class); }
}


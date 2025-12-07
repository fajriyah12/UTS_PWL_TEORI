<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Organizer extends Model
{
    use HasFactory, HasUuid;

    // Penting untuk UUID (pastikan properti ini ada)
    public $incrementing = false;
    protected $keyType   = 'string';

    // Tentukan tabel (optional, karena nama model sudah sesuai)
    protected $table = 'organizers';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'user_id',
        'company_name',
        'phone',
        'address',
        'is_verified',
        'bank_account',
        'bank_name'
    ];

    // Casting data jika perlu
    protected $casts = [
        // jika ada boolean atau datetime
    ];

    /**
     * Relasi ke User
     * Satu organizer dimiliki oleh satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Event
     * Satu organizer memiliki banyak event
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Scope untuk organizer yang aktif (jika ada kolom status)
     */
    public function scopeActive($query)
    {
        // Jika ada kolom status
        // return $query->where('status', 'active');
        return $query;
    }

    /**
     * Get URL organizer
     */
    public function getUrlAttribute()
    {
        return route('organizer.profile', $this->slug);
    }
}
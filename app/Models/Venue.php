<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Venue extends Model
{
    use HasFactory, HasUuid;

    // Penting untuk UUID
    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'venues';

    protected $fillable = [
        'name',
        'address',
        'city',
        'capacity',
        'description'
    ];

    /**
     * Relasi ke Event
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}

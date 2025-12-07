<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasUuid;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role', 'email_verified_at',
        'date_of_birth', 'gender', 'institution', 'occupation', 'photo',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // Penting untuk UUID (pastikan properti ini ada)
    public $incrementing = false;
    protected $keyType   = 'string';

    // Helper role
    public function isAdmin(): bool      { return $this->role === 'admin'; }
    public function isOrganizer(): bool  { return $this->role === 'organizer'; }

    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_email', 'email');
    }

    public function organizer()
    {
        return $this->hasOne(Organizer::class);
    }
}

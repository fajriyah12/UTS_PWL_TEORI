<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'ticket_type_id', 'buyer_name', 'buyer_email',
        'quantity', 'total_price', 'order_code'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        static::creating(function ($order) {
            $order->id = (string) Str::uuid();
        });
    }
}

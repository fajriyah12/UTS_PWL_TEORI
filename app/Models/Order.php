<?php

namespace App\Models;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    use HasUuid;
    protected $fillable=['code','user_id','total_amount','status','payment_method','paid_at','meta'];
    protected $casts=['paid_at'=>'datetime','meta'=>'array'];
    public function user(){ return $this->belongsTo(User::class); }
    public function items(){ return $this->hasMany(OrderItem::class); }
}

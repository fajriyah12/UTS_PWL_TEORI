<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Venue extends Model
{
    use HasFactory;
    use HasUuid;
    public $incrementing = false;
protected $keyType = 'string';
    
}

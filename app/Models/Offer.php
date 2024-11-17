<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type', 'per', 'min_price', 'details', 'start_at', 'expire_at', 'package'];
}

<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class License extends Model
{
    use HasFactory;
    protected $table = 'licenses';
    protected $fillable = ['account_id', 'license' , 'status'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}

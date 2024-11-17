<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsPattern extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['category_id', 'name', 'text', 'page', 'cost', 'status'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(SmsPatternCategory::class, 'category_id');
    }
}

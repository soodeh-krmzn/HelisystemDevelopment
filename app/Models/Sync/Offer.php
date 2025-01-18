<?php

namespace App\Models\Sync;

use App\Models\MyModels\Sync;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Offer extends Sync
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
}

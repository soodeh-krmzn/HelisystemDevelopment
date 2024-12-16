<?php

namespace App\Models\Sync;

use App\Models\MyModels\Sync;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class CounterItem extends Sync
{
    use HasFactory;

    protected $guarded = [];
}

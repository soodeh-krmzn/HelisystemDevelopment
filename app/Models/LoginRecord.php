<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginRecord extends Model
{
    use HasFactory;
    protected $table = 'login_records';



    public function scopeFilter($query)
    {
        if (request()->filled('username')) {
            $query->where('username', 'LIKE', "%" . request('username') . "%");
        }
        if (request()->filled('ip')) {
            $query->where('ip', 'LIKE', "%" . request('ip') . "%");
        }
        if (request()->filled('os')) {
            $query->where('device', 'LIKE', "%" . request('os') . "%");
        }
        if (request()->filled('browser')) {
            $query->where('browser', 'LIKE', "%" . request('browser') . "%");
        }
        if (request()->filled('from')) {
            $from = verta()->parse(request('from'))->datetime();
            $query->where('created_at', '>', $from);
        }
        if (request()->filled('to')) {
            $to = verta()->parse(request('to'))->toCarbon()->addDay();
            $query->where('created_at', '<', $to);
        }
    }
}

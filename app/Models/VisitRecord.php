<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VisitRecord extends Model
{
    use HasFactory;
    protected $table = 'visit_records';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function scopeFilter($query)
    {
        if (request()->filled('account')) {
            $query->where('account_id', request('account'));
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
    public function scopeActivity($query,$default)
    {
        if (request()->filled('days')) {
            $date=now()->subDays(request('days'))->startOfDay();
            $query->select('account_id', DB::raw('MAX(created_at) as last_activity'))
            ->where('created_at', '<', $date)
            ->whereNotIn('account_id', function ($query) use ($date) {
                $query->select('account_id')
                      ->from('visit_records')
                      ->where('created_at', '>', $date);
            })
            ->groupBy('account_id')
            ->latest();

        }else {
            $date=now()->subDays($default)->startOfDay();
            $query->select('account_id', DB::raw('MAX(created_at) as last_activity'))
            ->where('created_at', '<', $date)
            ->whereNotIn('account_id', function ($query) use ($date) {
                $query->select('account_id')
                      ->from('visit_records')
                      ->where('created_at', '>', $date);
            })
            ->groupBy('account_id')
            ->latest();
        }
    }
}

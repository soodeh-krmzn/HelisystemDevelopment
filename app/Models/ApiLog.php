<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'endpoint',
        'method',
        'account_id',
        'user_name',
        'request_data',
        'response_data',
        'status_code',
        'ip_address',
    ];

    protected $casts = [
        'request_data' => 'json',
        'response_data' => 'json',
    ];

    protected $table = 'api_logs';

    public function deleteOldLogs()
    {
        ApiLog::where('created_at', '<', now()->subMonths(6))->delete();
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiLog;

class ApiLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $logData = [
            'endpoint' => $request->path(),
            'method' => $request->method(),
            'account_id' => $request->user()->account_id ?? null,
            'user_name' => $request->user()->username ?? null,
            'request_data' => " ", 
            'response_data' =>" ",
            'status_code' => $response->getStatusCode(),
            'ip_address' => $request->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        ApiLog::create($logData);

        return $response;
    }

    private function prepareData($data)
    {
        if (is_array($data)) {
            return json_encode($data); 
        }

        if (is_string($data)) {
            return $data; 
        }

        return json_encode((array) $data);
    }
}

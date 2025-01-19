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

        if (!ApiLog::where('endpoint', $request->path())
            ->where('method', $request->method())
            ->where('created_at', '>=', now()->subSeconds(1)) // جلوگیری از لاگ مشابه در یک ثانیه
            ->exists()) {
            ApiLog::create([
                'endpoint' => $request->path(),
                'method' => $request->method(),
                'account_id' => $request->attributes->get('account_id'),
                'user_name' => $request->attributes->get('username'),
                'request_data' => json_encode($request->all(), JSON_UNESCAPED_UNICODE),
                'response_data' => json_encode(json_decode($response->getContent(), true), JSON_UNESCAPED_UNICODE),
                'status_code' => $response->getStatusCode(),
                'ip_address' => $request->ip(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


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

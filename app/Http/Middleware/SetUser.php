<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiLog;
use App\Models\User;

class SetUser
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
        // استخراج اطلاعات کاربر از درخواست (مثال: username, licenseKey و ...)
        $username = $request->input('username');
        $licenseKey = $request->input('licenseKey');

        // یافتن کاربر و حساب مرتبط
        $user = User::where('username', $username)->first();
        $accountId = $user?->account_id;

        // ذخیره اطلاعات در attributes
        $request->attributes->set('account_id', $accountId);
        $request->attributes->set('username', $username);

        return $next($request);
    }
}

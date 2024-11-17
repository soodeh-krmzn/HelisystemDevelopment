<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\Dump;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('setLang')) {
            $languages = ['en', 'fa'];
            $lang = in_array($request->setLang, $languages) ? $request->setLang : 'fa';
            if ($lang == 'fa') {
                Cookie::queue(Cookie::forget('language'));
                Session::forget('language');
            } else {
                Session::put('language', $lang);
                app()->setLocale($lang);
                return $next($request)->withCookie(cookie('language', $lang, 1500 * 365));
            }
        } elseif ($lang = Session::get('language')) {
            app()->setLocale($lang);
        } elseif ($lang = $request->cookie('language')) {
            Session::put('language', $lang);
            app()->setLocale($lang);
        }
        return $next($request);
    }
}

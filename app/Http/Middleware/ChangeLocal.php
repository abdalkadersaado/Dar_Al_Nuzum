<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class ChangeLocal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // $accept_language = $request->header('Accept-Language');
        // $lang_array = explode(',',$accept_language);
        // $locale = $lang_array[0] ?? App::getLocale();

        // Session::put('lang',$locale);

        // App::setlocale($locale);

        app()->setLocale('ar');


        if($request->header('Accept-Language') == 'en')
            app()->setLocale('en');


        return $next($request);
    }
}

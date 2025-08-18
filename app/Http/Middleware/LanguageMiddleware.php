<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = '';
        if ($request->expectsJson()) {
            $locale = $request->header('X-Language');
        } else {
            $locale = $request->cookie('X-Language');
        }
        // When there is wrong locale set to default english language
        if (!in_array($locale, ['en', 'fr'])) {
            $locale = 'fr';
        }

       // dd($locale);
     
        App::setLocale($locale);
        return $next($request);
    }
}

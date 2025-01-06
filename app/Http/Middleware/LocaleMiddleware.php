<?php 

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LocaleMiddleware 
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->header('X-Locale', 'en');
        
        App::setLocale($locale);
        return $next($request);
    }
}
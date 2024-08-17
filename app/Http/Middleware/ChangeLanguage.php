<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChangeLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
/*    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->input('lang', 'en');

        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        // Debugging
        \Log::info('Locale set to: ' . $locale);

        return $next($request);
    }*/

    public function handle(Request $request, Closure $next): Response
    {
        // Get the 'lang' parameter from the route or default to 'en'
        $locale = $request->route('lang', $request->query('lang', 'en'));

        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}

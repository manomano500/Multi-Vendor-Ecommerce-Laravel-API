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
    public function handle(Request $request, Closure $next): Response
    {
        // Default to English if 'lang' parameter is not set or invalid
        $locale = $request->input('lang', 'en');

        // Validate 'lang' parameter
        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'en'; // Fallback to English if invalid value is provided
        }

        // Set the application locale
        app()->setLocale($locale);

        return $next($request);
    }
}

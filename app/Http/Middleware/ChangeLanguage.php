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
        // Get the 'lang' query parameter, default to 'en'
        $locale = $request->query('lang', 'en');

        // Validate the locale
        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'en'; // Fallback if the locale is not valid
        }

        // Set the application locale
        app()->setLocale($locale);

        return $next($request);
    }}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Add the Content-Security-Policy header
        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'nonce-12345';");

        return $response;
    }
}

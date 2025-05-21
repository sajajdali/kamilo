<?php
namespace App\Http\Middleware;

use Closure;

class AllowIframe
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set('X-Frame-Options', 'ALLOW-FROM https://consulting.armanelahie.com');
        $response->headers->set('Access-Control-Allow-Origin', 'https://consulting.armanelahie.com');
        $response->headers->set('Content-Security-Policy', 'frame-ancestors https://consulting.armanelahie.com');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        return $response;
    }
}
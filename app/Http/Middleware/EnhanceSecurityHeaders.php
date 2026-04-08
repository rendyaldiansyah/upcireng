<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnhanceSecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Gunakan headers->set() bukan ->header()
        // karena StreamedResponse & BinaryFileResponse tidak punya method header()
        $headers = [
            'X-Frame-Options'           => 'SAMEORIGIN',
            'X-Content-Type-Options'    => 'nosniff',
            'X-XSS-Protection'          => '1; mode=block',
            'Referrer-Policy'           => 'strict-origin-when-cross-origin',
            'Permissions-Policy'        => 'camera=(), microphone=(), geolocation=()',
            'Content-Security-Policy'   => "default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.tailwindcss.com; font-src 'self' data: https://fonts.gstatic.com; img-src 'self' data: https:; connect-src 'self' https: ws: wss:; media-src 'self' https:; object-src 'none'; frame-ancestors 'self';",
        ];

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
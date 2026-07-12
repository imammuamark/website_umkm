<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security headers compliance (OWASP recommendations)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(), usb=()');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        // CSP - Solid baseline allowing self-assets, Google Fonts, and Google tag scripts
        $csp = "default-src 'self'; "
             ."script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.googletagmanager.com https://connect.facebook.net; "
             ."style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; "
             ."font-src 'self' https://fonts.gstatic.com data:; "
             ."img-src 'self' data: blob: https:; "
             ."media-src 'self' blob:; "
             ."worker-src 'self' blob:; "
             ."frame-src 'self' https://www.google.com https://www.googletagmanager.com https://www.youtube-nocookie.com https://player.vimeo.com; "
             ."connect-src 'self' https://www.google-analytics.com https://stats.g.doubleclick.net; "
             ."base-uri 'self'; form-action 'self'; object-src 'none';";

        if ($request->isSecure() && app()->environment('production')) {
            $csp .= ' upgrade-insecure-requests;';
        }
        $response->headers->set('Content-Security-Policy', $csp);

        // HSTS (Only enforce on HTTPS/Production to avoid breaking local HTTP dev servers)
        if ($request->isSecure() && app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Don't add headers for OPTIONS requests as Nginx handles those
        if (!$request->isMethod('OPTIONS')) {
            $origin = $request->header('Origin');
            $allowedOrigins = [
                'https://cars-admin.milltech.ai',
                'https://milltech.ai',
                'http://localhost:3000'
            ];

            if (in_array($origin, $allowedOrigins)) {
                $response->headers->set('Access-Control-Allow-Origin', $origin);
                $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
                $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-XSRF-TOKEN, X-Auth-Token, Origin, Accept');
                $response->headers->set('Access-Control-Allow-Credentials', 'true');
                $response->headers->set('Access-Control-Max-Age', '86400');
            }
        }

        return $response;
    }
} 
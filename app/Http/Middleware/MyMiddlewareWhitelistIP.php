<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MyMiddlewareWhitelistIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // L'adresse IP à whitelister
        $whitelistedIp = '127.0.0.1'; 

        // Si l'adresse IP ne correspond pas à celle whitelistée, retourne une erreur 403
        if ($request->ip() !== $whitelistedIp) {
            return response('Votre adresse IP n\'est pas autorisée.', 403);
        }

        return $next($request); // Autorise la requête si l'IP est valide
    }
}

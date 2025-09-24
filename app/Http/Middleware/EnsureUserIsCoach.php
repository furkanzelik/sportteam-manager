<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsCoach
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Controleer of de gebruiker is ingelogd én een coach is
        if (!auth()->check() || auth()->user()->role !== Role::Coach) {
            abort(403, 'Alleen coaches hebben toegang tot dit gedeelte.');
        }

        return $next($request);
    }
}

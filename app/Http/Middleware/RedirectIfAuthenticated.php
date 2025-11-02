<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     Als een ingelogde gebruiker naar login/register gaat, stuur hem door.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        if (auth()->check()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}

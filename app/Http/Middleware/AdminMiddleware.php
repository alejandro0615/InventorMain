<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authorizedEmail = 'torresgiraldojhon1224@outlook.es';

        if (!auth()->check() || !auth()->user()->isAdmin() || auth()->user()->email !== $authorizedEmail) {
            return redirect()->route('principal')->with('error', 'Acceso restringido. Solo el Super Administrador puede entrar.');
        }

        return $next($request);
    }
}

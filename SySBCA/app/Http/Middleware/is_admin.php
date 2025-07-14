<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class is_admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    if (auth()->check() && auth()->user()->role->nom_role !== 'Administrateur') {
        auth()->logout();
        return redirect('/login')->withErrors([
            'error' => 'Connectez vous pour accéder à cette page!'
        ]);
    }

    return $next($request);
}

}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class fsOrDistrict
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if (auth()->check() && !in_array($user->role->nom_role, ['Formation sanitaire', 'District'])) {
        auth()->logout();
        return redirect('/login')->withErrors([
            'error' => 'Connectez vous pour accéder à cette page!'
        ]);
    }
        return $next($request);
    }
}

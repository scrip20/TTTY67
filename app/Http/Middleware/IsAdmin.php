<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Maneja una solicitud entrante.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica si el usuario actual está autenticado y si su rol es 'admin'
        if (Auth::user() &&  Auth::user()->role == 'admin') {
            // Si el usuario es un administrador, permite que la solicitud continúe al siguiente middleware o controlador
            return $next($request);
        }

        // Si el usuario no es un administrador, redirige a la ruta 'stories' con un parámetro de consulta 'level=1'
        return redirect(route('stories').'?level=1');
    }
}
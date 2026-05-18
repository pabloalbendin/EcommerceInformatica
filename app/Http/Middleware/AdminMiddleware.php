<?php

// Namespace del middleware dentro de la aplicación
namespace App\Http\Middleware;

// Importa la clase Closure para continuar con la ejecución del middleware
use Closure;

// Importa la clase Request para manejar la petición HTTP
use Illuminate\Http\Request;

// Importa la clase Response (no se usa directamente, pero forma parte del middleware)
use Symfony\Component\HttpFoundation\Response;

// Importa la fachada Auth para comprobar la autenticación del usuario
use Illuminate\Support\Facades\Auth;

// Middleware encargado de restringir el acceso solo a administradores
class AdminMiddleware
{
    // Maneja la petición antes de llegar al controlador
    public function handle($request, Closure $next)
    {
        // Comprueba si el usuario no está autenticado o no tiene rol de administrador
        if (!Auth::check() || Auth::user()->id_rol != 2) {
            
            // Si no cumple las condiciones, lanza un error 403 (acceso prohibido)
            abort(403);
        }

        // Si el usuario es administrador, permite continuar con la petición
        return $next($request);
    }

}

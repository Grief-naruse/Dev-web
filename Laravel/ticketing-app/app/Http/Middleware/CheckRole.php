<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Sécurité de base : si la personne n'est pas connectée, dehors !
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Le contrôle strict : on compare le rôle de l'utilisateur avec le rôle exigé
        // (On gère aussi le cas où un "admin" a le droit de tout voir par défaut)
        $userRole = Auth::user()->role;
        
        if ($userRole !== $role && $userRole !== 'admin') {
            // Renvoie une belle page d'erreur 403 (Accès Interdit) native à Laravel
            abort(403, 'Accès Refusé : Vous n\'avez pas les droits nécessaires pour consulter cet espace.');
        }

        // 3. Le badge est valide, on le laisse passer à la page suivante
        return $next($request);
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  mixed  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (!$user || !in_array($user->role, $roles)) {
            Log::warning('Tentative d\'accès non autorisée.', [
                'user_id' => $user->id ?? null,
                'roles_attendus' => $roles,
                'role_actuel' => $user->role ?? 'guest',
                'url' => $request->fullUrl(),
            ]);

            // Retourne une page 403 personnalisée
            abort(403, 'Vous n’avez pas les permissions requises pour accéder à cette page.');
        }

        return $next($request);
    }
}

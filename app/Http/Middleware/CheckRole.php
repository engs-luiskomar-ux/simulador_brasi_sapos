<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $usuario = $request->user();
        $roleAtual = $usuario?->role;
        $roleAtual = $roleAtual instanceof UserRole ? $roleAtual->value : $roleAtual;

        abort_unless($usuario && in_array($roleAtual, $roles, true), 403, 'Você não possui permissão para acessar esta área.');

        return $next($request);
    }
}

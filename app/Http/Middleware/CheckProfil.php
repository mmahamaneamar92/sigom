<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfil
{
    public function handle(
        Request $request,
        Closure $next,
        ...$profils
    ): Response {

        if (!auth()->check()) {

            abort(
                401,
                'Vous devez être connecté.'
            );
        }

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($user->profil === 'admin') {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Vérification
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $user->profil,
                $profils
            )
        ) {

            abort(
                403,
                'Vous n\'êtes pas autorisé à effectuer cette opération.'
            );
        }

        return $next($request);
    }
}
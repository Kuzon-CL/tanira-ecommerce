<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_if($user === null, 403, 'Anda tidak memiliki akses ke halaman admin.');
        abort_unless($user->isAdmin(), 403, 'Anda tidak memiliki akses ke halaman admin.');

        return $next($request);
    }
}

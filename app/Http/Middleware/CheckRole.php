<?php

namespace App\Http\Middleware;

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
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Jika user adalah kasir, dan mencoba akses halaman selain /pos atau profile, tendang ke /pos
        if ($user && $user->role === 'kasir') {
            return redirect()->route('pos.index')->with('error', 'Akses Ditolak: Anda hanya dapat mengakses halaman Kasir POS.');
        }

        return $next($request);
    }
}

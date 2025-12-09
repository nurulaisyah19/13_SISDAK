<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): \Symfony\Component\HttpFoundation\Response  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (! $user) {
            // belum login
            return redirect()->route('login');
        }

        if ($user->role !== $role) {
            // role tidak sesuai - redirect ke dashboard yang sesuai dengan role mereka
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'ormawa') {
                return redirect()->route('ormawa.dashboard');
            }
            // fallback ke login jika role tidak dikenal
            return redirect()->route('login');
        }

        return $next($request);
    }
}

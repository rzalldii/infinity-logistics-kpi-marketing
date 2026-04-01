<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            return $next($request);
        }
        foreach ($roles as $role) {
            if ($user->role === $role) {
                return $next($request);
            }
        }
        abort(403);
    }
}

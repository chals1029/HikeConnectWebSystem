<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }
        if (! $user->isAdmin()) {
            if ($user->isTourGuide()) {
                return redirect()->route('tour-guide.dashboard');
            }
            return redirect()->route('hikers.dashboard');
        }
        return $next($request);
    }
}

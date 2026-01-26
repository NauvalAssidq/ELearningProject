<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlacementCompleted
{
    /**
     * Handle an incoming request.
     * Redirect students to placement quiz if they haven't completed it.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Only apply to authenticated students
        if ($user && $user->hasRole('student') && !$user->hasCompletedPlacement()) {
            // Don't redirect if already on placement routes
            if (!$request->routeIs('placement.*')) {
                return redirect()->route('placement.index')
                    ->with('info', 'Silakan selesaikan tes penempatan terlebih dahulu.');
            }
        }

        return $next($request);
    }
}

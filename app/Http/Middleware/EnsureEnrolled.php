<?php

namespace App\Http\Middleware;

use App\Models\Module;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEnrolled
{
    /**
     * Handle an incoming request.
     * Check if student is enrolled in the module before allowing access to lessons.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $module = $request->route('module');

        // Resolve module if it's a string (slug)
        if (is_string($module)) {
            $module = Module::where('slug', $module)->first();
        }

        if (!$module) {
            abort(404, 'Modul tidak ditemukan.');
        }

        // Check if student is enrolled
        if ($user && $user->hasRole('student') && !$user->isEnrolledIn($module)) {
            return redirect()->route('student.modules.show', $module)
                ->with('warning', 'Anda harus mendaftar ke modul ini terlebih dahulu.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Module;
use App\Models\QuizAttempt;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin')) {
             return $this->adminView();
        }

        if ($user->hasRole('admin')) {
             return $this->adminView();
        }

        if ($user->hasRole('lecturer')) {
            return $this->lecturerView();
        }

        return $this->studentView();
    }

    private function studentView()
    {
        $user = request()->user();
        
        // Check if student needs to complete placement first
        if (!$user->hasCompletedPlacement()) {
            return redirect()->route('placement.index');
        }

        // Get modules matching student's skill level (can see at or below their level)
        $availableModules = Module::forUserLevel($user)
            ->withCount('lessons')
            ->get();
        
        // Get enrolled modules
        $enrolledModules = $user->enrolledModules()->withCount('lessons')->get();
        
        // Calculate stats
        $totalModules = $availableModules->count();
        $enrolledCount = $enrolledModules->count();
        $grade = round($user->quizAttempts()->avg('score'), 1) ?? 0;
        
        // Get current active module (first enrolled module with incomplete lessons)
        $activeModule = $enrolledModules->first();

        return view('student.dashboard', compact(
            'user',
            'availableModules', 
            'enrolledModules',
            'totalModules',
            'enrolledCount', 
            'grade', 
            'activeModule'
        ));
    }

    private function adminView()
    {
        $totalUsers = \App\Models\User::count();
        return view('admin.dashboard', compact('totalUsers'));
    }

    private function lecturerView()
    {
        $user = auth()->user();
        
        // Get modules created by this lecturer (or all if no user_id filter needed)
        $modules = Module::withCount(['lessons', 'enrollments'])
            ->with(['enrollments' => function($q) {
                $q->latest('enrolled_at')->limit(5);
            }, 'enrollments.user'])
            ->latest()
            ->get();
        
        $totalModules = $modules->count();
        $totalLessons = $modules->sum('lessons_count');
        $totalEnrolledStudents = $modules->sum('enrollments_count');
        
        // Get recent enrollments across all modules
        $recentEnrollments = \App\Models\Enrollment::with(['user', 'module'])
            ->latest('enrolled_at')
            ->limit(5)
            ->get();

        // --- Chart Data: Learning Effectiveness ---

        // 1. Student Grade Distribution (Bell Curve Analysis)
        // Aggregating all best quiz scores for all students enrolled in these modules
        $allQuizAttempts = \App\Models\QuizAttempt::whereHas('quiz.lesson.module', function($q) use ($modules) {
                $q->whereIn('id', $modules->pluck('id'));
            })
            ->select('score')
            ->get();
        
        $gradeDistribution = [
            'needs_help' => $allQuizAttempts->where('score', '<', 60)->count(),
            'passing'    => $allQuizAttempts->whereBetween('score', [60, 84])->count(),
            'excellent'  => $allQuizAttempts->where('score', '>=', 85)->count(),
        ];

        // 2. Completion Funnel (Retention Rate)
        $totalEnrollmentsCount = \App\Models\Enrollment::whereIn('module_id', $modules->pluck('id'))->count();
        $completedEnrollmentsCount = \App\Models\Enrollment::whereIn('module_id', $modules->pluck('id'))
            ->whereNotNull('completed_at')
            ->count();
        
        $completionStats = [
            'active' => $totalEnrollmentsCount - $completedEnrollmentsCount,
            'completed' => $completedEnrollmentsCount,
            'rate' => $totalEnrollmentsCount > 0 ? round(($completedEnrollmentsCount / $totalEnrollmentsCount) * 100, 1) : 0
        ];

        // 3. Module Effectiveness Score
        // Average score per module to see which content is most effective
        $moduleEffectiveness = $modules->map(function($module) {
            $avgScore = $module->lessons->flatMap->quiz->flatMap->attempts->avg('score');
            return [
                'title' => \Illuminate\Support\Str::limit($module->title, 15),
                'score' => round($avgScore ?? 0, 1),
                'total_students' => $module->enrollments_count
            ];
        })->sortByDesc('score')->values();
        
        return view('lecturer.dashboard', compact(
            'user',
            'modules',
            'totalModules',
            'totalLessons',
            'totalEnrolledStudents',
            'recentEnrollments',
            'gradeDistribution',
            'completionStats',
            'moduleEffectiveness'
        ));
    }
}

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
        // Aggregating average of BEST quiz scores per student
        // Step 1: Get the MAX score for each user per quiz (handle retries)
        $bestQuizScores = \Illuminate\Support\Facades\DB::table('quiz_attempts')
            ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
            ->join('lessons', 'quizzes.lesson_id', '=', 'lessons.id')
            ->whereIn('lessons.module_id', $modules->pluck('id'))
            ->select('quiz_attempts.user_id', 'quiz_attempts.quiz_id', \Illuminate\Support\Facades\DB::raw('MAX(quiz_attempts.score) as max_score'))
            ->groupBy('quiz_attempts.user_id', 'quiz_attempts.quiz_id');

        // Step 2: Average those best scores per user
        $studentFinalScores = \Illuminate\Support\Facades\DB::table(\Illuminate\Support\Facades\DB::raw("({$bestQuizScores->toSql()}) as best_scores"))
            ->mergeBindings($bestQuizScores)
            ->select('user_id', \Illuminate\Support\Facades\DB::raw('AVG(max_score) as avg_final_score'))
            ->groupBy('user_id')
            ->get();
        
        $gradeDistribution = [
            'needs_help' => $studentFinalScores->where('avg_final_score', '<', 60)->count(),
            'passing'    => $studentFinalScores->whereBetween('avg_final_score', [60, 84])->count(),
            'excellent'  => $studentFinalScores->where('avg_final_score', '>=', 85)->count(),
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
            // Calculate average score for this module directly via database
            // We also want the average of BEST attempts here to be consistent, or just average of all attempts?
            // Usually effectiveness implies "how well do students perform", so average of best scores is fairer.
            // But for simplicity and performance in a loop, we'll take the average of all attempts for now, 
            // or we can implement the same "best score" logic if needed. 
            // Let's stick to simple average of all attempts for the module to catch if many people fail initially.
            $avgScore = \App\Models\QuizAttempt::whereHas('quiz.lesson', function($q) use ($module) {
                $q->where('module_id', $module->id);
            })->avg('score');

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

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LecturerStudentController extends Controller
{
    public function index()
    {
        $lecturerId = Auth::id();

        // Get students who are enrolled in ANY module created by this lecturer
        $students = User::whereHas('roles', function($q) {
                $q->where('slug', 'student');
            })
            ->whereHas('enrolledModules', function($q) use ($lecturerId) {
                $q->where('modules.user_id', $lecturerId);
            })
            ->with(['enrolledModules' => function($q) use ($lecturerId) {
                $q->where('modules.user_id', $lecturerId)->with(['lessons', 'quiz']); // Eager load quiz for grade calc
            }, 'lessonProgress', 'quizAttempts.quiz']) // Eager load quiz relation on attempts
            ->get();

        // Calculate progress and grade for each student
        $students->transform(function($student) {
            $modules = $student->enrolledModules;
            
            if ($modules->isEmpty()) {
                $student->avg_progress = 0;
                $student->avg_grade = 0;
                $student->enrolled_count = 0;
                $student->modules_data = [];
                return $student;
            }

            $totalProgress = 0;
            $moduleCount = $modules->count();
            $modulesData = [];

            foreach ($modules as $module) {
                $totalLessons = $module->lessons->count();
                $lessonIds = $module->lessons->pluck('id');
                
                $completedLessons = $student->lessonProgress
                    ->whereIn('lesson_id', $lessonIds)
                    ->where('is_completed', true)
                    ->count();
                
                $progress = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
                $totalProgress += $progress;

                // Calculate grade for this specific module
                $moduleAttempts = $student->quizAttempts->filter(function($attempt) use ($module) {
                    return $attempt->quiz && $attempt->quiz->module_id === $module->id;
                });
                
                $moduleGrade = $moduleAttempts->avg('score') ?? 0;

                $modulesData[] = [
                    'id' => $module->id,
                    'title' => $module->title,
                    'progress' => round($progress),
                    'grade' => round($moduleGrade),
                    'slug' => $module->slug
                ];
            }

            $student->avg_progress = round($totalProgress / $moduleCount);
            // Global average grade
            $student->avg_grade = round($student->quizAttempts->avg('score') ?? 0);
            $student->enrolled_count = $moduleCount;
            $student->modules_data = $modulesData;

            return $student;
        });

        return view('lecturer.students.index', compact('students'));
    }
}

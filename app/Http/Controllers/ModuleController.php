<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModuleController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // For students, filter by their skill level (can see modules at or below their level)
        if ($user->hasRole('student')) {
            $modules = Module::forUserLevel($user)
                ->withCount(['lessons', 'enrollments'])
                ->latest()
                ->get();
            return view('student.modules.index', compact('modules', 'user'));
        }
        
        // For lecturers, show all modules with enrollment counts
        $modules = Module::withCount(['lessons', 'enrollments'])->with('quiz')->latest()->get();
        return view('lecturer.modules.index', compact('modules'));
    }

    public function create()
    {
        return view('lecturer.modules.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'skill_level' => 'required|in:pemula,menengah,mahir',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('modules', 'public');
            $validated['image'] = 'storage/' . $path;
        }

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);
        $validated['user_id'] = auth()->id();
        // skill_level is already validated and in $validated

        Module::create($validated);

        return redirect()->route('lecturer.modules.index')->with('success', 'Modul berhasil dibuat.');
    }

    public function show(Module $module)
    {
        $module->load([
            'lessons' => function($query) {
                $query->orderBy('order');
            },
            'quiz.questions',
            'projectSubmissions'
        ]);
        
        // Return different views based on user role
        if (auth()->user()->hasRole('student')) {
            return view('student.modules.show', compact('module'));
        }
        
        return view('lecturer.modules.show', compact('module'));
    }

    public function edit(Module $module)
    {
        return view('lecturer.modules.edit', compact('module'));
    }

    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'skill_level' => 'required|in:pemula,menengah,mahir',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($module->image) {
                $oldPath = str_replace('storage/', '', $module->image);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
            
            $path = $request->file('image')->store('modules', 'public');
            $validated['image'] = 'storage/' . $path;
        } elseif ($request->has('remove_image') && $request->remove_image == '1') {
             if ($module->image) {
                $oldPath = str_replace('storage/', '', $module->image);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
            $validated['image'] = null;
        }

        if ($module->title !== $validated['title']) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);
        }

        $module->update($validated);

        return redirect()->route('lecturer.modules.index')->with('success', 'Modul berhasil diperbarui.');
    }

    public function destroy(Module $module)
    {
        if ($module->image) {
             $oldPath = str_replace('storage/', '', $module->image);
             \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
        }
        
        $module->delete();
        return redirect()->route('lecturer.modules.index')->with('success', 'Modul berhasil dihapus.');
    }

    /**
     * Show enrolled learners for a module (lecturer only).
     */
    public function learners(Module $module)
    {
        $learners = $module->enrolledStudents()
            ->withPivot('enrolled_at')
            ->orderByPivot('enrolled_at', 'desc')
            ->get();

        return view('lecturer.modules.learners', compact('module', 'learners'));
    }

    /**
     * Remove a student from a module.
     */
    public function kick(Module $module, \App\Models\User $user)
    {
        // Ensure the module belongs to the authenticated lecturer
        if ($module->user_id !== auth()->id()) {
            abort(403);
        }

        // 1. Delete Project Submission and File
        $submission = \App\Models\ProjectSubmission::where('module_id', $module->id)
            ->where('user_id', $user->id)
            ->first();

        if ($submission) {
            if (Storage::disk('public')->exists($submission->file_path)) {
                Storage::disk('public')->delete($submission->file_path);
            }
            $submission->delete();
        }

        // 2. Delete Lesson Progress
        // Get all lesson IDs for this module
        $lessonIds = $module->lessons()->pluck('id');
        
        \App\Models\LessonProgress::whereIn('lesson_id', $lessonIds)
            ->where('user_id', $user->id)
            ->delete();

        // 3. Delete Quiz Attempts
        // Find quizzes belonging to these lessons
        $quizIds = \App\Models\Quiz::whereIn('lesson_id', $lessonIds)->pluck('id');
        
        \App\Models\QuizAttempt::whereIn('quiz_id', $quizIds)
            ->where('user_id', $user->id)
            ->delete();

        // 4. Remove Enrollment
        \App\Models\Enrollment::where('module_id', $module->id)
            ->where('user_id', $user->id)
            ->delete();

        return redirect()->back()->with('success', 'Siswa berhasil dikeluarkan dari modul.');
    }
}

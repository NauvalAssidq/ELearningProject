<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ProjectSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectSubmissionController extends Controller
{
    public function create(Module $module)
    {
        // INTEGRITY CHECK: Ensure student has passed all quizzes
        if (!$module->hasCompletedAllLessons(auth()->user())) {
             return redirect()->route('student.modules.show', $module)
                ->with('error', 'Anda harus menyelesaikan semua pelajaran di modul ini sebelum mengerjakan proyek akhir.');
        }

        $existingSubmission = ProjectSubmission::where('module_id', $module->id)
            ->where('user_id', auth()->id())
            ->first();

        return view('student.projects.create', compact('module', 'existingSubmission'));
    }

    public function store(Request $request, Module $module)
    {
        // INTEGRITY CHECK: Prevent backend bypass
        if (!$module->hasCompletedAllLessons(auth()->user())) {
             abort(403, 'Anda belum menyelesaikan semua pelajaran.');
        }

        $blockedExtensions = ['php', 'exe', 'bat', 'sh', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js', 'jar', 'app', 'deb', 'pkg'];
        
        $request->validate([
            'project_file' => [
                'required',
                'file',
                'max:51200', // 50MB in kilobytes
                function ($attribute, $value, $fail) use ($blockedExtensions) {
                    $extension = strtolower($value->getClientOriginalExtension());
                    if (in_array($extension, $blockedExtensions)) {
                        $fail('File type not allowed for security reasons.');
                    }
                },
            ],
        ]);

        // Delete existing submission if any
        $existing = ProjectSubmission::where('module_id', $module->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            // Delete old file
            if (Storage::disk('public')->exists($existing->file_path)) {
                Storage::disk('public')->delete($existing->file_path);
            }
            $existing->delete();
        }

        $file = $request->file('project_file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('projects/' . $module->id, $filename, 'public');

        ProjectSubmission::create([
            'module_id' => $module->id,
            'user_id' => auth()->id(),
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
        ]);

        return redirect()->route('student.modules.show', $module)
            ->with('success', 'Proyek berhasil disubmit!');
    }

    public function show(Module $module, ProjectSubmission $submission)
    {
        // Ensure user can only view their own submission
        if ($submission->user_id !== auth()->id()) {
            abort(403);
        }

        return view('student.projects.show', compact('module', 'submission'));
    }

    /**
     * List all submissions for a module (Lecturer)
     */
    public function index(Module $module)
    {
        $submissions = ProjectSubmission::where('module_id', $module->id)
            ->with('user')
            ->latest('submitted_at')
            ->get();

        $gradedCount = $submissions->whereNotNull('grade')->count();
        $pendingCount = $submissions->whereNull('grade')->count();

        return view('lecturer.submissions.index', compact('module', 'submissions', 'gradedCount', 'pendingCount'));
    }

    /**
     * Review a single submission (Lecturer)
     */
    public function review(Module $module, ProjectSubmission $submission)
    {
        $submission->load('user');
        
        return view('lecturer.submissions.review', compact('module', 'submission'));
    }

    /**
     * Grade a submission (Lecturer)
     */
    public function grade(Request $request, Module $module, ProjectSubmission $submission)
    {
        $validated = $request->validate([
            'grade' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $submission->update([
            'grade' => $validated['grade'],
            'feedback' => $validated['feedback'],
        ]);

        // Check verification and update enrollment status
        $this->checkAndMarkCompletion($module, $submission->user);

        return redirect()->route('lecturer.submissions.index', $module)
            ->with('success', 'Nilai berhasil disimpan untuk ' . $submission->user->name);
    }
    /**
     * Show form for lecturer to manual upload submission.
     */
    public function createLecturer(Module $module)
    {
        // Get students enrolled in this module
        $students = $module->enrolledStudents()->orderBy('name')->get();
        return view('lecturer.submissions.create', compact('module', 'students'));
    }

    /**
     * Store manual submission by lecturer.
     */
    public function storeLecturer(Request $request, Module $module)
    {
        $blockedExtensions = ['php', 'exe', 'bat', 'sh', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js', 'jar', 'app', 'deb', 'pkg'];

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'project_file' => [
                'required',
                'file',
                'max:51200',
                function ($attribute, $value, $fail) use ($blockedExtensions) {
                    $extension = strtolower($value->getClientOriginalExtension());
                    if (in_array($extension, $blockedExtensions)) {
                        $fail('File type not allowed for security reasons.');
                    }
                },
            ],
            'grade' => 'nullable|integer|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        // Ensure student is enrolled (optional check but good for integrity)
        if (!$module->enrolledStudents()->where('users.id', $validated['user_id'])->exists()) {
             return back()->with('error', 'Siswa tidak terdaftar dalam modul ini.');
        }

        // Handle existing submission
        $existing = ProjectSubmission::where('module_id', $module->id)
            ->where('user_id', $validated['user_id'])
            ->first();

        if ($existing) {
            if (Storage::disk('public')->exists($existing->file_path)) {
                Storage::disk('public')->delete($existing->file_path);
            }
            $existing->delete();
        }

        $file = $request->file('project_file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('projects/' . $module->id, $filename, 'public');

        $submission = ProjectSubmission::create([
            'module_id' => $module->id,
            'user_id' => $validated['user_id'],
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'grade' => $validated['grade'] ?? null,
            'feedback' => $validated['feedback'] ?? null,
        ]);

        // Check completion if grade is provided
        if ($submission->grade !== null) {
            $user = \App\Models\User::find($validated['user_id']);
            $this->checkAndMarkCompletion($module, $user);
        }

        return redirect()->route('lecturer.submissions.index', $module)
            ->with('success', 'Proyek berhasil diupload manual untuk mahasiswa tersebut.');
    }

    /**
     * Show form to edit project requirements.
     */
    public function editRequirements(Module $module)
    {
        return view('lecturer.submissions.requirements', compact('module'));
    }

    /**
     * Update project requirements.
     */
    public function updateRequirements(Request $request, Module $module)
    {
        $validated = $request->validate([
            'project_instruction' => 'nullable|string',
            'project_attachment' => 'nullable|file|max:51200',
        ]);

        if ($request->hasFile('project_attachment')) {
            // Delete old attachment
            if ($module->project_attachment) {
                $oldPath = str_replace('storage/', '', $module->project_attachment);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
            
            $path = $request->file('project_attachment')->store('project_assignments', 'public');
            $module->project_attachment = 'storage/' . $path;
        } elseif ($request->has('remove_project_attachment') && $request->remove_project_attachment == '1') {
             if ($module->project_attachment) {
                $oldPath = str_replace('storage/', '', $module->project_attachment);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
            $module->project_attachment = null;
        }

        $module->project_instruction = $validated['project_instruction'];
        $module->save();

        return redirect()->route('lecturer.submissions.index', $module)
            ->with('success', 'Persyaratan proyek berhasil diperbarui.');
    }

    /**
     * Check if module is completed and update enrollment
     */
    private function checkAndMarkCompletion(Module $module, \App\Models\User $user)
    {
        if ($module->isCompletedBy($user)) {
            \App\Models\Enrollment::where('module_id', $module->id)
                ->where('user_id', $user->id)
                ->whereNull('completed_at')
                ->update(['completed_at' => now()]);
        }
    }
}
